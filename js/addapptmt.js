/**
 * Created by yura on 08.02.17.
 */




//waiting for page loaded
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        const yearChangeEvent = new Event('change');
        var meetMonh = document.getElementById("meet_month");
        var meetYear = document.getElementById("meet_year");
        var radioRecurring = document.getElementsByName("is_recurr");
        for (var i = 0; i < radioRecurring.length; i++) {
            radioRecurring[i].addEventListener("change", function () {
                radioRecurringChangeHandler(radioRecurring);
            }, false);
        }
        meetMonh.addEventListener("change", function (e) {
            monthChangeHandler(this);
        }, false);
        meetYear.addEventListener("change", function (e) {
            meetMonh.dispatchEvent(yearChangeEvent);
        }, false);
        document.getElementById('form').reset();
    }
}


function radioRecurringChangeHandler(e) {
    var periodGroup = document.getElementsByName("period");

    // if (e) {
    for (var i = 0; i < e.length; i++) {
        if (e[i].checked) {
            if (e[i].value ==1) {
                for (var p = 0; p < periodGroup.length; p++) {
                    periodGroup[p].disabled = false;
                    console.log("enable pls" + e[i].value);
                }
            }
            else{
                for (var p = 0; p < periodGroup.length; p++) {
                    periodGroup[p].disabled =true;
                            }

            }

        }
    }
}
function fillDaySelector(num) {
    var meeDay = document.getElementById('meet_day');
    meeDay.innerHTML = '';
    console.log(meeDay);
    for (var i = 1; i <= num; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        meeDay.appendChild(opt);
    }
}

function monthChangeHandler(e) {
    var selectedM = parseInt(e.options[e.selectedIndex].value);

    switch (selectedM) {
        case 4:
        case 6:
        case 9:
        case 11:
            fillDaySelector(30);
            break;
        case 2:
            var meetYear = document.getElementById("meet_year");
            console.log(meetYear);

            var selectedY = meetYear.options[meetYear.selectedIndex].value;
            if (selectedY % 4 == 0)
                fillDaySelector(29);
            else
                fillDaySelector(28);
            break;

        default:
            fillDaySelector(31);
            break;
    }
}