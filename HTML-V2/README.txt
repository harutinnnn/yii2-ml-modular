ASUE static prototype — September 2026 document review applied

index.html = homepage
REVIEW-CHANGES.md = change coverage, verification, and remaining integration work

The package includes public inner-page templates and representative pages required by the technical specification: university/about, governance, structure, faculties, departments, education/programs, admissions, science/research/journals, faculty profiles, student services, international, news/announcements, events/calendar/event registration presentation, public information/documents, global search, contact, student/teacher login and dashboard prototypes.

Run from a local HTTP server for consistent shared browser storage:
  python -m http.server 8765 --bind 127.0.0.1
Then open http://127.0.0.1:8765/index.html

The public pages are static. Login, student requests, publication approvals,
quick-link configuration, and schedule publishing are local demonstrations.
They use sessionStorage/localStorage, not a production authentication service
or shared database. The login screen explicitly identifies the demo.

Use login.html to choose Student, Teacher, Administrative employee, or
Publication reviewer. Administrative employees manage quick links and import
Excel schedules. Only Publication reviewers approve teacher submissions.

Schedule imports accept .xlsx (up to 5 MB, 5000 data rows per worksheet).
Choose a worksheet and map its header columns to date/day, time, group,
subject, teacher, and room. Preview before publishing. A new upload replaces
the same faculty/level/schedule-kind combination in this browser only.
The university's final Excel template has not yet been supplied.

site-config.js contains default quick links and selectable public pages.
review-changes.css contains the review's shared layout changes.
prototype.js implements local workflows; schedule-import.js reads XLSX values.
search-index.js contains the static public-page search index.

Verification:
  python tools/make_schedule_fixture.py
  node tools/verify_review.cjs
The browser test uses the bundled Playwright package and installed Edge.
Test fixtures and screenshots are written to .work; do not publish that folder.


The original files are backed up in .work/before-document-changes.zip.
