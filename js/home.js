/**
 * Controls actions and features of the dayplans (hame) page
 * Authors: Chad Drennan, Bridget Black
 */

let selectedDayNum;

// Event to filter by muscle group
$('.muscle-group').on('click', filterWorkouts);

$('.workout').on('click', addOrRemoveWorkoutToSelection);

$('#add-weights-reps').on('click', addWorkouts);


/**
 * Controls the appearance of the workout selection modal
 */
$('#workout-modal').on('show.bs.modal', function (event) {

    // Change modal heading to match day of week selected
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    // track day the modal is selecting workouts for
    selectedDayNum = button.data('day-num');

    let modal = $(this);
    modal.find('#workout-modal-title').text('Add a workout for ' + dayOfWeek);

    // Unselected all workouts
    $('.selected').toggleClass('btn-primary').toggleClass('btn-secondary').toggleClass('selected');

    // Remove filters to show all workouts
    $('.workout').show();

    $('.muscle-group.btn-primary').removeClass('btn-primary');
    $('#all-muscle-groups').addClass('btn-primary');
});


$('#weight-reps-modal').on('show.bs.modal', function (event) {
    // Refresh table
    $('#weights-reps-modal-body tbody').html('');

    // Show each workout and inputs
    $('.selected').each(function() {
        let workout = $(this).text().trim();
        let workoutIdFormat = workout.toLowerCase().replace(' ', '-');

        $('#weights-reps-modal-body tbody').append(
            `<tr>
                <th>` + workout + `</th>
                <td><input id="` + workoutIdFormat + `-weight" type="number" class="form-control"></td>
                <td><input id="` + workoutIdFormat + `-reps" type="number" class="form-control"></td>
            </tr>`);
    });
});

/**
 * Filters all workouts buttons by the muscle group selected
 */
function filterWorkouts() {
    $('.muscle-group.btn-primary').removeClass('btn-primary');
    $(this).addClass('btn-outline-primary');

    $(this).toggleClass('btn-primary').toggleClass('btn-outline-primary');

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
 * Toggles a workout selection for a dayplan
 */
function addOrRemoveWorkoutToSelection() {
    $(this).toggleClass('selected').toggleClass('btn-primary').toggleClass('btn-secondary');
}

function addWorkouts() {
    $('.selected').each(function() {
        let workout = $(this).text().trim();
        let workoutIdFormat = workout.toLowerCase().replace(' ', '-');

        let weight = $('#' + workoutIdFormat + '-weight').val();
        let reps = $('#' + workoutIdFormat + '-reps').val();

        let workoutLogData = {workout: workout, dayAdjustment: selectedDayNum, weight: weight, reps: reps};

        $.post('/328/WorkoutTracker/log-workout', workoutLogData, function(result) {
            $("#test").append(result);
        });

        $('#day-' + selectedDayNum + ' tbody').append(
            `<tr>
                <th>` + workout + `</th>
                <td>` + weight + `</td>
                <td>` + reps + `</td>
            </tr>`)
    });
}
