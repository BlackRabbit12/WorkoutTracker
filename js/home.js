/**
 * Controls actions and features of the dayplans (home) page
 *
 * @author Chad Drennan
 * @author Bridget Black
 * @version 1.0
 * Last Updated: 2020-03-15
 */

let selectedDayNum;

// Event to filter by muscle group
$('.muscle-group').on('click', filterWorkouts);

//Event to add or remove a workout
$('.workout').on('click', addOrRemoveWorkoutToSelection);

//add a workout with details
$('#add-weights-reps').on('click', addWorkouts);

//get all workouts from database and subtract out previous two days of card's selected workouts
$('#suggestion-modal').on('click', suggestWorkouts);

// Edit workout event
$('#edit-workouts').on('click', editWorkouts);

$('.delete-mode').on('click', toggleDeleteMode);


/**
 * Controls the appearance of the workout selection modal.
 */
$('#workout-modal').on('show.bs.modal', function (event) {

    // Change modal heading to match day of week selected
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    // Track day the modal is selecting workouts for
    selectedDayNum = button.data('day-num');

    let modal = $(this);
    modal.find('#workout-modal-title').text('Add Workouts for ' + dayOfWeek);

    // Unselected all workouts
    $('.selected').toggleClass('btn-primary').toggleClass('btn-secondary').toggleClass('selected');

    // Remove filters to show all workouts
    $('.workout').show();

    $('.muscle-group.btn-primary').removeClass('btn-primary');
    $('#all-muscle-groups').addClass('btn-primary');
});

/**
 * Refreshes table and displays workouts with inputs.
 */
$('#weight-reps-modal').on('show.bs.modal', function (event) {
    let tbody = $('#weights-reps-modal-body tbody');

    // Refresh table
    tbody.html('');

    // Show each workout and inputs
    $('.selected').each(function() {
        let workout = $(this).text().trim();
        let workoutIdFormat = workout.toLowerCase().replace(' ', '-');

        tbody.append(
            `<tr>
                <th>` + workout + `</th>
                <td><input id="` + workoutIdFormat + `-weight" type="number" class="form-control"></td>
                <td><input id="` + workoutIdFormat + `-reps" type="number" class="form-control"></td>
            </tr>`
        );
    });
});

$('#edit-workout-modal').on('show.bs.modal', function (event) {

    // Change modal heading to match day of week selected
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    // Track day the modal is editing workouts for
    selectedDayNum = button.data('day-num');

    let modal = $(this);
    modal.find('#edit-workout-modal-title').text('Edit Workouts for ' + dayOfWeek);

    let tBody = $('#edit-workout-modal tbody');

    // Refresh table
    tBody.html('');

    $('#day-' + selectedDayNum + ' tbody>tr').each(function() {
        let workoutLogId = $(this).data('log-id');
        let workout = $(this).find('.workout-name').text();
        let weight = $(this).find('.weight').text();
        let reps = $(this).find('.reps').text();

        tBody.append(
            `<tr data-log-id="` + workoutLogId + `">
                <th>` + workout + `</th>
                <td><input type="number" class="form-control weight" value="` + weight + `"></td>
                <td><input type="number" class="form-control reps" value="` + reps + `"></td>
            </tr>`
        );
    });
});

$('#suggestion-modal').on('show.bs.modal', function (event) {
    $.get('/328/WorkoutTracker/not-selected-workouts', function(result) {
        let workouts = JSON.parse(result);

        // Refresh modal
        $('#workout-suggestion').html('');

        $(workouts).each(function() {
            $('#workout-suggestion').append('<button class="btn btn-secondary btn-sm mb-1">' + this + '</button>');
        });

    });
});

/**
 * Filters all workouts buttons by the muscle group selected.
 */
function filterWorkouts() {

    // Highlight only selected muscle group filter
    $('.muscle-group.btn-primary').removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).addClass('btn-primary').removeClass('btn-outline-primary');


    let selectedMuscleGroup = $(this).text().trim();
    let allWorkouts = $('.workout');

    allWorkouts.show();

    // Do not filter for all muscle groups
    if (selectedMuscleGroup === 'All') {
        return;
    }

    // Hides workouts not in selected muscle group
    allWorkouts.filter(function(index) {
       return $.inArray(selectedMuscleGroup, $(this).data('muscle-groups')) === -1;
    }).hide();
}

/**
 * Toggles a workout selection for a day plan.
 */
function addOrRemoveWorkoutToSelection() {
    $(this).toggleClass('selected').toggleClass('btn-primary').toggleClass('btn-secondary');
}

/**
 * Adds a workout with the weight/rep details.
 */
function addWorkouts() {
    $('.selected').each(function() {
        let workout = $(this).text().trim();
        let workoutIdFormat = workout.toLowerCase().replace(' ', '-');

        let weight = $('#' + workoutIdFormat + '-weight').val();
        let reps = $('#' + workoutIdFormat + '-reps').val();

        let workoutLogData = {workout: workout, dayAdjustment: selectedDayNum, weight: weight, reps: reps};

        $.post('/328/WorkoutTracker/log-workout', workoutLogData, function(result) {
        });

        $('#day-' + selectedDayNum + ' tbody').append(
            `<tr>
                <th>` + workout + `</th>
                <td>` + weight + `</td>
                <td>` + reps + `</td>
            </tr>`
        );
    });
}

function editWorkouts() {
    let rows = $('#edit-workout-modal tbody>tr');

    rows.each(function() {
        let currRow = $(this);
        let workoutLogId = currRow.data('log-id');
        let weight = currRow.find('.weight').val();
        let reps = currRow.find('.reps').val();

        let workoutLogData = {workoutLogId: workoutLogId, weight: weight, reps: reps};

        $.post('/328/WorkoutTracker/edit-workout', workoutLogData, function(result) {
        });

        let workoutLog = $('#day-' + selectedDayNum + ' [data-log-id="' + workoutLogId + '"]');
        workoutLog.find('.weight').text(weight);
        workoutLog.find('.reps').text(reps);
    });
}

function toggleDeleteMode() {
    let dayNum = $(this).data('day-num');

    // If in delete mode, exit delete mode
    if ($(this).hasClass('delete-mode-active')) {
        // Remove blank th
        $('#day-' + dayNum + ' thead>tr').children().last().remove();

        // Remove X symbol on every row
        $('#day-' + dayNum + ' tbody>tr').each(function () {
            $(this).children().last().remove();
        });
    }
    else { // Enter delete mode

        // Add blank th for correct formatting in thead
        $('#day-' + dayNum + ' thead>tr').append('<th></th>');

        // Add X symbol in body for every row
        $('#day-' + dayNum + ' tbody>tr').each(function () {
            $(this).append(`<td><button type="button" class="delete-log close text-danger">&times;</button></td>`);
        });

        $('.delete-log').on('click', deleteWorkoutLog);
    }
    $(this).toggleClass('delete-mode-active');
}

function deleteWorkoutLog() {
    let workoutLogId = $(this).parent().parent().data('log-id');

    $.post('/328/WorkoutTracker/delete-workout', {workoutLogId: workoutLogId}, function(result) {
    });

    $('[data-log-id="' + workoutLogId + '"]').remove();
}

function suggestWorkouts() {
    $.post('{{ @BASE/suggest }}', function (result) {

    });
}
