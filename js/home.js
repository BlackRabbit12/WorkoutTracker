$('#add-workout').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let dayOfWeek = button.data('day');

    let modal = $(this);
    modal.find('.modal-title').text('Add a workout for ' + dayOfWeek);
});
