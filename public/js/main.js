$(".delete-article").click("click", function () {
  if (confirm("Are you sure you want to delete this?")) {
    $.ajax({
      url: `/articles/delete/${$(this).data("id")}`,
      method: "DELETE",
    }).done(function (data) {
      window.location.reload();
    });
  }
});
