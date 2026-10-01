<?php

$facultyTitle = $faculty?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";
$chairTitle = $chair?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";

?>
<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow">Ուսանողի անձնական գրասենյակ</div>
            <h1>Բարի գալուստ, <?= $user->additional->first_name ?></h1>
            <p>Կենտրոնացված հասանելիություն անձնական տվյալներին, դիմումներին, նամակագրությանը և գիտական
                պորտֆոլիոյին։</p></div>
        <div class="status-box">
            <strong>Գործող ուսանող</strong>
            <span>
                <?= $facultyTitle ?> · <?= $user->additional->course ?>-<?= $user->additional->course == 1 ? 'ին' : 'րդ' ?> կուրս · K-301
            </span>
        </div>
    </section>
    <section id="profile" class="module-section">
        <h2>Անձնական տվյալներ</h2>
        <div class="form-grid">
            <label class="field">Անուն
                <input value="<?= $user->additional->first_name ?>" disabled>
            </label>
            <label class="field">Ազգանուն
                <input value="<?= $user->additional->last_name ?>" disabled>
            </label>
            <label class="field">Ծննդյան տարեթիվ
                <input value="<?= $user->additional->dob ?>" disabled>
            </label>
            <label class="field">Անձնագրային տվյալներ
                <input value="••••••••" disabled>
            </label>
            <label class="field">Հեռախոս
                <input value="<?= $user->additional->phone ?>">
            </label>
            <label class="field">Էլ. փոստ
                <input value="<?= $user->email ?>">
            </label>
            <label class="field">Ֆակուլտետ / մասնագիտություն
                <input value="<?= $facultyTitle ?> / <?= $chairTitle ?>" disabled>
            </label>
        </div>

        <button class="btn">Պահպանել թույլատրելի փոփոխությունները</button>
    </section>
    <section id="card" class="module-section"><h2>Էլեկտրոնային ուսանողական տոմս</h2>
        <div class="panel"><span class="badge">ACTIVE</span>
            <h3><?= $user->additional->first_name ?> <?= $user->additional->last_name ?></h3>
            <p>ID: ASUE-2023-00481</p>
            <p> <?= $facultyTitle ?> · <?= $chairTitle ?></p>
            <p class="meta">Կարգավիճակը թարմացվում է ավտոմատ և կարող է կիրառվել գրադարանի ու անցագրային
                համակարգերում։</p></div>
    </section>
    <section id="requests" class="module-section"><h2>Դիմումներ, բողոքներ և առաջարկներ</h2>
        <div class="module-tabs">
            <button class="btn">Նոր դիմում</button>
            <span class="pill">Տեղեկանք</span><span class="pill">Արձակուրդ</span><span
                    class="pill">Տեղափոխություն</span><span class="pill">IT / Moodle / email</span><span
                    class="pill">Բողոք</span><span class="pill">Առաջարկ</span></div>
        <table class="data-table">
            <thead>
            <tr>
                <th>N</th>
                <th>Տեսակ</th>
                <th>Ներկայացվել է</th>
                <th>Պատասխանատու</th>
                <th>Կարգավիճակ</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>0245</td>
                <td>Տեղեկանք</td>
                <td>17.08.2026</td>
                <td>Փաստաթղթաշրջանառություն</td>
                <td><span class="badge">Մշակման մեջ</span></td>
            </tr>
            <tr>
                <td>0231</td>
                <td>Moodle մուտք</td>
                <td>12.08.2026</td>
                <td>ՏՏ բաժին</td>
                <td><span class="badge">Հաստատված</span></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section id="portfolio" class="module-section"><h2>Գիտական պորտֆոլիո</h2>
        <form data-demo-form>
            <div class="form-grid"><label class="field">Աշխատանքի անվանում<input required></label><label class="field">Տեսակ<select>
                        <option>Հոդված</option>
                        <option>Զեկույց</option>
                        <option>Հետազոտություն</option>
                    </select></label><label class="field">Ֆայլ կամ URL<input></label></div>
            <button class="btn" type="submit">Ավելացնել</button>
            <div class="notice hidden" data-confirmation>Գիտական աշխատանքն ավելացվել է պորտֆոլիոյում։</div>
        </form>
    </section>
</main>