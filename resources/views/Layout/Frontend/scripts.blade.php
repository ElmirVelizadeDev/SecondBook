<script src="{{ asset('frontend-assets/js/jquery-1.11.0.min.js') }}"></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"
></script>

<script src="{{ asset('frontend-assets/js/plugins.js') }}"></script>

<script src="{{ asset('frontend-assets/js/script.js') }}"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const profileDropdown =
        document.querySelector('.profile-dropdown');

    if (!profileDropdown) {
        return;
    }

    profileDropdown.addEventListener(
        'show.bs.dropdown',
        function () {
            profileDropdown.classList.add('show');
        }
    );

    profileDropdown.addEventListener(
        'hide.bs.dropdown',
        function () {
            profileDropdown.classList.remove('show');
        }
    );

});
</script>

