$('.muscle-group').on('click', filterWorkouts);


$('#add-workout').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    let modal = $(this);
    modal.find('.modal-title').text('Add a workout for ' + dayOfWeek);
});


function filterWorkouts() {
    let muscleGroup = $(this).text();
    let workouts = $('.workout');

    workouts.show();

    if (muscleGroup === 'All') {
        return;
    }

    workouts.filter(function(index) {
       return $.inArray(muscleGroup, $(this).data('muscle-groups')) === -1;
    }).hide();
}
