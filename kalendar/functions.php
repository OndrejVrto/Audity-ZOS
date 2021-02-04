<?php
    
    // kod je stiahnutý so stránky
    // https://www.codexworld.com/build-event-calendar-using-jquery-ajax-php/

if (!class_exists('db')){
    include_once $_SERVER['DOCUMENT_ROOT'] . '/include/_autoload.php';
}

/* 
 * Load function based on the Ajax request
 */
if (isset($_POST['func']) && !empty($_POST['func'])) {
    switch ($_POST['func']) {
        case 'getCalender':
            getCalender($_POST['year'], $_POST['month']);
            break;
        case 'getEvents':
            getEvents($_POST['date']);
            break;
        default:
            break;
    }
}

/* 
 * Generate event calendar in HTML format 
 */
function getCalender($year = '', $month = '')
{
    $dateYear = ($year != '') ? $year : date("Y");
    $dateMonth = ($month != '') ? $month : date("m");
    $date = $dateYear . '-' . $dateMonth . '-01';
    $currentMonthFirstDay = date("N", strtotime($date));
    $totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
    $totalDaysOfMonthDisplay = ($currentMonthFirstDay == 1) ? ($totalDaysOfMonth) : ($totalDaysOfMonth + ($currentMonthFirstDay - 1));
    $boxDisplay = ($totalDaysOfMonthDisplay <= 35) ? 35 : 42;

    $prevMonth = date("m", strtotime('-1 month', strtotime($date)));
    $prevYear = date("Y", strtotime('-1 month', strtotime($date)));
    $totalDaysOfMonth_Prev = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
?>

    <main class="calendar-contain">
        <section class="title-bar">
            <a href="javascript:void(0);" class="title-bar__prev" onclick="getCalendar('calendar_div','<?php echo date("Y", strtotime($date . ' - 1 Month')); ?>','<?php echo date("m", strtotime($date . ' - 1 Month')); ?>');"></a>
            <div class="title-bar__month">
                <select class="month-dropdown">
                    <?php echo getMonthList($dateMonth); ?>
                </select>
            </div>
            <a href="javascript:void(0);" class="title-bar__prev today-button" onclick="getCalendar('calendar_div','<?php echo date("Y"); ?>','<?php echo date("m"); ?>');"></a>
            <div class="title-bar__year">
                <select class="year-dropdown">
                    <?php echo getYearList($dateYear); ?>
                </select>
            </div>
            <a href="javascript:void(0);" class="title-bar__next" onclick="getCalendar('calendar_div','<?php echo date("Y", strtotime($date . ' + 1 Month')); ?>','<?php echo date("m", strtotime($date . ' + 1 Month')); ?>');"></a>
        </section>

        <aside class="calendar__sidebar" id="event_list">
            <?php echo getEvents().PHP_EOL; ?>
        </aside>

        <section class="calendar__days">
            <section class="calendar__top-bar">
                <span class="top-bar__days">Pon</span>
                <span class="top-bar__days">Uto</span>
                <span class="top-bar__days">Str</span>
                <span class="top-bar__days">Štv</span>
                <span class="top-bar__days">Pia</span>
                <span class="top-bar__days">Sob</span>
                <span class="top-bar__days">Ned</span>
            </section>

            <?php
            $dayCount = 1;
            $eventNum = 0;

            echo '<section class="calendar__week">';
            for ($cb = 1; $cb <= $boxDisplay; $cb++) {
                if (($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)) {
                    // Current date 
                    $currentDate = $dateYear . '-' . $dateMonth . '-' . $dayCount;

                    // Get number of events based on the current date 
                    global $db;
                    $result = $db->query("SELECT title FROM events WHERE date = '" . $currentDate . "' AND status = 1");
                    $eventNum = $result->numRows();

                    // Define date cell color 
                    if (strtotime($currentDate) == strtotime(date("Y-m-d"))) {
                        echo ' 
                <div class="calendar__day today" onclick="getEvents(\'' . $currentDate . '\');"> 
                    <span class="calendar__date">' . $dayCount . '</span> 
                    <span class="calendar__task calendar__task--today">' . $eventNum . ' Udalostí</span> 
                </div> 
                            ';
                    } elseif ($eventNum > 0) {
                        echo ' 
                <div class="calendar__day event" onclick="getEvents(\'' . $currentDate . '\');"> 
                    <span class="calendar__date">' . $dayCount . '</span> 
                    <span class="calendar__task">' . $eventNum . ' Udalostí</span> 
                </div> 
                            ';
                    } else {
                        echo ' 
                <div class="calendar__day no-event" onclick="getEvents(\'' . $currentDate . '\');"> 
                    <span class="calendar__date">' . $dayCount . '</span> 
                    <!-- <span class="calendar__task">' . $eventNum . ' Udalostí</span> -->
                </div> 
                            ';
                    }
                    $dayCount++;
                } else {
                    if ($cb < $currentMonthFirstDay) {
                        $inactiveCalendarDay = ((($totalDaysOfMonth_Prev - $currentMonthFirstDay) + 1) + $cb);
                        $inactiveLabel = 'uplynul';
                    } else {
                        $inactiveCalendarDay = ($cb - $totalDaysOfMonthDisplay);
                        $inactiveLabel = 'nadchádza';
                    }
                    echo ' 
                <div class="calendar__day inactive"> 
                    <span class="calendar__date">' . $inactiveCalendarDay . '</span> 
                    <!-- <span class="calendar__task">' . $inactiveLabel . '</span>  -->
                </div> 
                        ';
                }
            echo ($cb % 7 == 0 && $cb != $boxDisplay) ? PHP_EOL.TAB3.'</section>'.PHP_EOL.TAB3.'<section class="calendar__week">' : '';
            }
            echo PHP_EOL.TAB3.'</section>'.PHP_EOL;
            ?>
        </section>
    </main>

    <?php
}

    function getSkriptyKalendar(){

ob_start();
?>
    <script nonce="<?= $GLOBALS["nonce"] ?>">
        function getCalendar(target_div, year, month) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: 'func=getCalender&year=' + year + '&month=' + month,
                success: function(html) {
                    $('#' + target_div).html(html);
                }
            });
        }

        function getEvents(date) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: 'func=getEvents&date=' + date,
                success: function(html) {
                    $('#event_list').html(html);
                }
            });
        }

        $(document).ready(function() {
            $('.month-dropdown').on('change', function() {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val());
            });
            $('.year-dropdown').on('change', function() {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val());
            });
            $('.today-button').on('click', function(){
                var d = new Date();
                getCalendar('calendar_div', d.getFullYear() , d.getMonth());
            });
        });
    </script>
<?php
    return ob_get_clean();
}

/* 
 * Generate months options list for select box 
 */
function getMonthList($selected = '')
{
    $options = '';
    for ($i = 1; $i <= 12; $i++) {
        $value = ($i < 10) ? '0' . $i : $i;
        $selectedOpt = ($value == $selected) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selectedOpt . ' >' . date("F", mktime(0, 0, 0, $i + 1, 0, 0)) . '</option>' . PHP_EOL . TAB5;
    }
    $options .= PHP_EOL;
    return $options;
}

/* 
 * Generate years options list for select box 
 */
function getYearList($selected = '')
{
    $yearInit = !empty($selected) ? $selected : date("Y");
    $yearPrev = ($yearInit - 5);
    $yearNext = ($yearInit + 5);
    $options = '';
    for ($i = $yearPrev; $i <= $yearNext; $i++) {
        $selectedOpt = ($i == $selected) ? 'selected' : '';
        $options .= '<option value="' . $i . '" ' . $selectedOpt . ' >' . $i . '</option>' . PHP_EOL . TAB5;
    }
    $options .= PHP_EOL;
    return $options;
}

/* 
 * Generate events list in HTML format 
 */
function getEvents($date = '')
{
    $date = $date ? date("Y-m-d", strtotime($date)) : date("Y-m-d");

    $eventListHTML = '<h2 class="sidebar__heading">' . date("l", strtotime($date)) . '<br>' . date("F d", strtotime($date)) . '</h2>';

    // Fetch events based on the specific date 
    global $db;
    $result = $db->query('SELECT `title` FROM `events` WHERE `date` = ? AND `status` = 1', $date);

    if ($result->numRows() > 0) {
        $eventListHTML .= '<ul class="sidebar__list">';
        $eventListHTML .= '<li class="sidebar__list-item sidebar__list-item--complete">Udalosti</li>';
        $i = 0;
        foreach ($result->fetchAll() as $key => $value) {
            $i++;
            $eventListHTML .= '<li class="sidebar__list-item"><span class="list-item__time">' . $i . '.</span>' . $value['title'] . '</li>';
        }
        $eventListHTML .= '</ul>';
    }
    echo $eventListHTML;
}
