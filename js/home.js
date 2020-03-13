/**
 * Controls actions and features of the dayplans (hame) page
 * Authors: Chad Drennan, Bridget Black
 */

// Event to filter by muscle group
$('.muscle-group').on('click', filterWorkouts);


/**
 * Controls the appearance of the workout selection modal
 */
$('#add-workout').on('show.bs.modal', function (event) {

    // Change modal heading to match day of week selected
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    let modal = $(this);
    modal.find('.modal-title').text('Add a workout for ' + dayOfWeek);

    // Remove filters to show all workouts
    $('.workout').show();
});


/**
 * Filters all workouts buttons by the muscle group selected
 */
function filterWorkouts() {
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
