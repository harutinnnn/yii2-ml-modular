# September 2026 document changes

Applied to the static prototype in this directory using `ՀՊՏՀ կայք-շտկումներ.docx` and the navigation lists on pages 4–8 of the adjacent `ASUE-SPECS.pdf`. Where the review updates the specification, the review takes precedence.

| Requested change | Implementation |
| --- | --- |
| Fix the top row | Fixed header, transparent at the top; after scrolling it gains a background and shrinks from 96px to 68px (80px to 64px on mobile). Sticky inner navigation follows its height. |
| Move Structure under About | The About menu includes the five specified structural categories and the structure overview. |
| Review or make quick links configurable | Four configurable links, with defaults for admissions, education, schedules, and student login; edited from the administrative dashboard. |
| Separate the department head | Dedicated head section above the staff list. Existing representative data retained. |
| Show staff as a list, first five plus dropdown | Six entries from the supplied lecturer directory moved into the department; five visible and the remaining entry in an expandable list. Each entry opens its own representative profile. |
| Remove teacher featured posts | Removed the decorative feature below the profile. |
| Exactly two teacher tabs, scientific articles/books, CV details | Accessible Basic information and Scientific activity tabs; separate article/book lists; experience, languages, certificates, and teaching subjects. |
| Teacher submission with required fields and authorized approval | Teacher form requires title, type, authors, year, publisher, and URL. Submissions remain pending until a separate reviewer approves them. Rejection requires a reason; editing and resubmission return an item to pending. Only approved items appear publicly. This is a local prototype workflow. |
| Remove education featured posts | Removed decorative feature panels from bachelor and other affected inner pages. |
| Rename educational programs and remove Program page | Renamed `Ուսումնական ծրագրեր` to `Կրթական ծրագրեր`; removed `program-detail.html`; updated all referring links to the education overview. |
| Remove or reduce repeated featured content | Removed generic editorial media and decorative split, mosaic, and photo-band sections. Retained relevant news/event/publication listings. |
| Remove Education environment; make video lectures standalone | Removed the redundant category; video lectures now have an independent education-menu entry. |
| Timetables and exam schedules with Excel import | Public pages, faculty/level/group filters, XLSX worksheet selection, column mapping, validation, preview, and local publication are implemented. Records use one normalized format. Final template-specific mapping awaits the university workbook. |
| Science: five direct entries | Electronic library, Scientific activity, Research centers, Conferences and seminars, Journals. No nested groups. |
| Simplify Student; remove certificate application | One direct list matching the specification. Removed the separate certificate service and moved requests to the student dashboard. Legacy request URLs lead to sign-in and contain no application form. |
| Remove standalone Lecturers menu | Removed the top-level category; the old directory entry directs visitors to departments. |
| Main Login entry and three or more user types | Student, Teacher, Administrative employee, and Publication reviewer demo roles. Student login remains accessible from Student. |
| Require student login before requests | Direct dashboard access is gated, forms appear only in the student session, and logout removes access. This demonstrates the intended UI; server-side authorization remains required. |
| Simplify International, retain department contact | Five specified direct links, plus contact with the International Relations department. |
| Keep News/Announcements together; separate search and relocate procurement/reporting | News menu contains News and Announcements. Advanced search is available in the header and search overlay. Procurement and budget/reporting are under About. |
| Remove public information from Contact; compact directory | Contact menu includes only contact details, map, department directory, and feedback. Department contacts use a compact searchable telephone-book table. |

## Verification

`tools/verify_review.cjs` exercises navigation, fixed header, staff expansion, separate profiles, accessible tabs, student access and request persistence, teacher submission, reviewer-only approval, rejection and resubmission, CV updates, quick-link configuration, XLSX preview/publication, schedule filters, invalid-file handling, search, contact filtering, and mobile widths. Static checks validate local links and assets and confirm the Science menu has five entries across shared public-page menus.

The browser tests use a disposable browser context. Their sample publications, requests, and schedules do not seed the delivered site or the user's browser.

## Remaining integration inputs

- **Official Excel template:** the review says the university will provide it. None was attached. The importer supports column mapping in the meantime; the fixed template and any merged-cell or multirow layout rules must be confirmed against that file.
- **Production backend:** the supplied project contains only static HTML/CSS/JavaScript. Real authentication, server-enforced roles/permissions, shared storage, actual request delivery, university-wide schedule publication, and approval audit history require a backend. Browser role selection and local storage are explicitly demo-only and are not a security boundary.
- **Institutional content:** existing sample names, contact details, and other representative content have been retained. The separated head section retains its supplied role description; a verified head identity and final CV data were not supplied.

No site was deployed, and no messages or applications were sent externally.
