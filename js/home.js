/**
 * Controls actions and features of the dayplans (hame) page
 * Authors: Chad Drennan, Bridget Black
 */

// Event to filter by muscle group
$('.muscle-group').on('click', filterWorkouts);

$('.workout').on('click', addOrRemoveWorkoutToSelection);

$('#add-workouts').on('click', addWorkouts);


/**
 * Controls the appearance of the workout selection modal
 */
$('#workout-modal').on('show.bs.modal', function (event) {

    // Change modal heading to match day of week selected
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');
    let dayNum = button.data('day-num');

    let modal = $(this);
    modal.find('.modal-title').text('Add a workout for ' + dayOfWeek);

    // Mark Add button with day the modal is selecting workouts for
    modal.find('#add-workouts').data('day-num', dayNum);

    // Unselected all workouts
    $('.selected').toggleClass('btn-primary').toggleClass('btn-secondary').toggleClass('selected');

    // Remove filters to show all workouts
    $('.workout').show();

    $('.muscle-group.btn-primary').removeClass('btn-primary');
    $('#all-muscle-groups').addClass('btn-primary');
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
    let dayNum = $(this).data('day-num');

    $('.selected').each(function() {
        $('#day-' + dayNum + " ul").append('<li class="list-group-item">' + $(this).text() + '</li>')
    });
}
