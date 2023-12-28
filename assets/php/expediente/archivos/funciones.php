<script>
 $("#tb").DataTable({
    pageLength: 5,
    drawCallback: function(settings) {
        ADP.show($("#tb")[0], "slide-left");
    },
});
</script>