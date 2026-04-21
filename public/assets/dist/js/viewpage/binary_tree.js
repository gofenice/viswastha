$(document).ready(function () {
    $('.no-user.clickable').click(function (e) {
        e.preventDefault();
        const parentId = $(this).data('parentId');
        const positionIndex = $(this).data('positionIndex');

        $.ajax({
            url: $('#tbl-binary-tree').data("user-url"),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Include CSRF token for security
            },
            contentType: 'application/json',
            data: JSON.stringify({
                parentId: parentId,
                positionIndex: positionIndex
            }),
            success: function (data) {
                if (data.success) {
                    const parent = data.parent;
                    // Set the values for specific form fields
                    $('#parent_id').val(parent.id || parentId);
                    $('#position_child').val(parent.position_child);
                    $('#position').val(parent.position);
                    $('#parent_name').val(data.parent.name + '-' + data.parent.connection);
                    $('#level').val(parent.level);
                    $('#user_code').val(parent.user_code);
                    $('#name, #email, #phone_no, #pan_card_no, #address, #password, #pincode')
                        .val('');

                    const $ancestorsSelect = $('#ancestors_select');
                    $ancestorsSelect.empty(); // Clear existing options

                    // Add the current parent as the first option
                    $('<option>')
                        .val(data.parent.id)
                        .text(data.parent.name + ' (Current Placement)'+ ' - ' + data.parent.connection)
                        .appendTo($ancestorsSelect);

                    // Add the ancestor options
                    data.ancestors.forEach(function (ancestor) {
                        $('<option>')
                            .val(ancestor.id)
                            .text(ancestor.name + ' - ' + ancestor.connection)
                            .appendTo($ancestorsSelect);
                    });

                    // // Populate the packages dropdown
                    // const $packagesSelect = $('#package_select');
                    // $packagesSelect.empty(); // Clear existing options

                    // // Add package options
                    // data.packages.forEach(function(package) {
                    //     $('<option>')
                    //         .val(package.id)
                    //         .text(package.name)
                    //         .appendTo($packagesSelect);
                    // });

                    $('#modal-lg').modal('show');
                } else {
                    alert('Failed to add user: ' + data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

    });

    $('#user-form').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            $('#user-form button[type="submit"]').prop('disabled', true);
        },
        success: function (responseText, statusText, xhr, $form) {
            const data = JSON.parse(responseText);
            console.log(data);
            $('#user-form button[type="submit"]').prop('disabled', false);
            $('#user-form .error-message').text("");
            if (data.status == "validation") {
                $.each(data.errors, function (key, val) {
                    $('[name="' + key + '"]').closest('.form-group').find(
                        '.error-message').text(val);
                })
            } else if (data.status == "success") {
                $form[0].reset();
                
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    title: "User added successfully!",
                    html: `
                        <p>Username: <strong>${data.connection}</strong></p>
                        <p>Password: <strong>${data.password}</strong></p>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            $('#user-form button[type="submit"]').prop('disabled', false);
            console.error(error, xhr, status);
        }
    });
});


$(document).ready(function () {
    const teamLink = $('.nav-link.team');
    const treeviewLink = $('.nav.nav-treeview.team');
    const mainLiLink = $('.nav-item.has-treeview.team');
    const binaryLink = $('.nav-link.binary');
    if (binaryLink.length) {
        binaryLink.addClass('active');
        teamLink.addClass('active');
        mainLiLink.addClass('menu-open');
        treeviewLink.css('display', 'block');
    }
});
// pin section-----------------------------
function updatepin(id) {
    var userid = $('#packageModal').find('#userid');
    userid.val(id);
    $('#updatePinForm .error-message').text("");
    $('#packageModal').modal('toggle');
}

$(document).ready(function () {
    $('#package_id').on('change', function () {
        const packageId = $(this).val();
        if (packageId) {
            $.ajax({
                url: '/get-available-pins', // Update this with the correct route
                method: 'GET',
                data: {
                    package_id: packageId
                },
                success: function (response) {
                    $('#pinId').empty().append(
                        '<option value="">--Choose Pin---</option>');
                    if (response.pins && response.pins.length > 0) {
                        response.pins.forEach(pin => {
                            $('#pinId').append(
                                `<option value="${pin.id}">${pin.unique_id}</option>`
                            );
                        });
                    } else {
                        $('#pinId').append(
                            '<option value="">No Pins Available</option>');
                    }
                },
                error: function () {
                    alert('Failed to fetch available pins. Please try again.');
                }
            });
        } else {
            $('#pinId').empty().append('<option value="">--Choose Pin---</option>');
        }
    });

    $('.btn-add-package').click(function (e) {
        e.preventDefault();
        const user_id = $(this).data('user');
        const packages = $(this).data('packages');

        var userid = $('#packageModal').find('#userid').val(user_id);
        $("#package_id option").each(function () {
            if (packages.includes(parseInt($(this).val()))) {
                $(this).hide(); // Hide the option
            } else {
                $(this).show(); // Hide the option
            }
        });
        $('#updatePinForm .error-message').text("");
        $('#packageModal').modal('toggle');
    });
});