<?php

namespace backend\modules\menu\controllers;

use backend\modules\menu\models\MenuForm;
use common\models\Menu;
use common\models\Section;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\db\Transaction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                ]],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $sections = Section::find()->orderBy(['position' => SORT_ASC, 'title' => SORT_ASC, 'id' => SORT_ASC])->all();
        $menus = Menu::find()
            ->with(['translations', 'content', 'section'])
            ->orderBy(['section_id' => SORT_ASC, 'position' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        return $this->render('index', compact('sections', 'menus'));
    }

    public function actionCreate(): string|Response
    {
        $model = new MenuForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Menu item created.');
            return $this->redirect(['index']);
        }

        return $this->render('create', compact('model'));
    }

    public function actionSlugify(string $title = '', ?int $menuId = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $baseSlug = Inflector::slug($title);
        if ($baseSlug === '') {
            return ['slug' => ''];
        }

        $slug = $baseSlug;
        $query = Menu::find()->where(['url' => $slug]);
        if ($menuId !== null) {
            $query->andWhere(['<>', 'id', $menuId]);
        }

        if ($query->exists()) {
            $slug .= '-' . substr(Yii::$app->security->generateRandomString(8), 0, 6);
        }

        return ['slug' => $slug];
    }

    public function actionSaveTree(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = Yii::$app->request->post('items', []);
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if (!is_array($items) || $items === []) {
            return ['success' => false, 'message' => 'Invalid payload.'];
        }

        $transaction = Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
        try {
            foreach ($items as $item) {
                $menu = Menu::findOne((int) ($item['id'] ?? 0));
                if ($menu === null) {
                    continue;
                }

                $depth = (int) ($item['depth'] ?? 1);
                if ($depth < 1 || $depth > 3) {
                    throw new \RuntimeException('Maximum depth is 3.');
                }

                $menu->section_id = (int) ($item['section_id'] ?? 0);
                $menu->parent_id = empty($item['parent_id']) ? null : (int) $item['parent_id'];
                $menu->position = (int) ($item['position'] ?? 0);
                if (!$menu->save(false, ['section_id', 'parent_id', 'position', 'updated_at'])) {
                    throw new \RuntimeException('Failed to save tree.');
                }
            }

            $transaction->commit();
            return ['success' => true];
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = new MenuForm($this->findModel($id));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Menu item updated.');
            return $this->redirect(['index']);
        }

        return $this->render('update', compact('model'));
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Menu item deleted.');
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Menu
    {
        $model = Menu::find()->with(['translations', 'content', 'section', 'parent'])->where(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('The requested menu item does not exist.');
        }
        return $model;
    }
}
