let previousStart = null;
document.addEventListener("DOMContentLoaded", function () {
    const calendar = new FullCalendar.Calendar(
        document.getElementById("calendar-box"),
        {
            initialView: "dayGridMonth",
            locale: "en",
            firstDay: 1,
            initialDate: caledarInitialDate, // December 2026
            height: "auto",
            fixedWeekCount: false,
            headerToolbar: {
                left: "title",
                center: "prev",
                right: "next"
            },
            buttonText: {
                prev: "<",
                next: ">"
            },

            datesSet: function (info) {
                const currentDate = calendar.getDate();

                const year = currentDate.getFullYear();
                const month = currentDate.getMonth() + 1;


                if (previousStart) {
                    if (info.start > previousStart) {
                        getCalendarData(year, month);
                    } else if (info.start < previousStart) {
                        getCalendarData(year, month);
                        console.log("User clicked PREV (or moved backward)");
                    }
                }
                previousStart = info.start;
            },

            dayCellDidMount: function (info) {
                const date = [
                    info.date.getFullYear(),
                    String(info.date.getMonth() + 1).padStart(2, "0"),
                    String(info.date.getDate()).padStart(2, "0")
                ].join("-");

                const event = window.calendarDates?.[date];

                if (event) {
                    // Background color
                    info.el.style.backgroundColor = event.color;

                    // Date number text color
                    const dayNumber = info.el.querySelector(".fc-daygrid-day-number");

                    if (dayNumber) {
                        dayNumber.style.color = event.textColor;
                    }

                    // Clickable date
                    info.el.style.cursor = "pointer";

                }
            }
        }
    );

    document.getElementById("calendar-box")
        .addEventListener("click", function(e) {
            const cell = e.target.closest(".fc-daygrid-day");

            if (!cell) return;

            const date = cell.dataset.date;
            const event = window.calendarDates?.[date];

            if (event?.url) {
                window.location.href = event.url;
            }
        });

    calendar.render();


});

function getCalendarData(year, month) {

    $.ajax({
        type: 'get',
        url: '/' + lang + '/events/get-calendar/' + parseInt(year) + '/' + parseInt(month),
        dataType: 'json',
        beforeSend: function (data) {

        },
        success: function (data) {
            updateCalendarDates(data);
        },
        error: function (error) {

        }
    })

}
function applyDayStyle(cell, event) {
    const dayNumber = cell.querySelector(".fc-daygrid-day-number");

    // Reset previous styling
    cell.style.backgroundColor = "";
    cell.style.cursor = "";

    if (dayNumber) {
        dayNumber.style.color = "";
    }

    if (event) {
        cell.style.backgroundColor = event.color || "rgb(127, 20, 36)";
        cell.style.cursor = "pointer";

        if (dayNumber) {
            dayNumber.style.color = event.textColor || "#ffffff";
        }
    }
}

function updateCalendarDates(data) {
    window.calendarDates = {};

    data.forEach(item => {
        window.calendarDates[item.day] = {
            color: item.color,
            textColor: item.textColor,
            url: item.url
        };
    });

    document.querySelectorAll("#calendar-box .fc-daygrid-day")
        .forEach(cell => {
            const date = cell.dataset.date;
            const event = window.calendarDates[date];

            applyDayStyle(cell, event);
        });
}