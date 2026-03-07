<!-- Default Footer Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/js/script.js') }}"></script>

<?php if (!isset($data['minimal_footer']) || !$data['minimal_footer']): ?>
    <!-- plugins:js -->
    {{-- carousel js --}}
    <script src="{{ asset('js/carousel.js') }}"></script>

    <script src="{{ asset('/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- End inject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('/js/dataTables.select.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('/js/template.js') }}"></script>
    <script src="{{ asset('/js/settings.js') }}"></script>
    <script src="{{ asset('/js/todolist.js') }}"></script>
    <!-- End inject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('/js/dashboard.js') }}"></script>
    <script src="{{ asset('/js/Chart.roundedBarCharts.js') }}"></script>
    <!-- End custom js for this page -->
<?php endif; ?>

</body>
</html>
