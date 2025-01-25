function getUrlParameter(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}


function updateUrlParameter(param, paramVal) {
  var url = new URL(window.location.href);
  url.searchParams.set(param, paramVal);
  window.history.pushState({}, "", url.href);
}

$(document).ready(function () {
  var table = $("#example").DataTable({
    responsive: true,
    info: true,
    lengthMenu: [25, 50, 100, 250, 500],
    pageLength: 25,
    dom:
      "<'row pt-2'<'col-sm-12 col-md-4'B><'col-sm-12 col-md-4 d-flex justify-content-center'l><'col-sm-12 col-md-4'f>>" +
      "<'row mt-2'<'col-sm-12'tr>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'i>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'p>>",
    buttons: [
      {
        extend: "print",
        className: "btn btn-info btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          stripHtml: false,
        },
      },
      {
        extend: "excelHtml5",
        className: "btn btn-success btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          format: {
            body: function (data, row, column, node) {
              data = data
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?span>/gi, "")
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?strong>/gi, "")
                .replace(/\n/g, "")
                .split("\n")
                .map((line) => line.trim())
                .join("\n");
              return data;
            },
          },
        },
      },
      {
        extend: "pdfHtml5",
        className: "btn btn-danger btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
        },
        customize: function (doc) {
          var body = doc.content[1].table.body;
          for (var i = 0; i < body.length; i++) {
            for (var j = 0; j < body[i].length; j++) {
              var cell = body[i][j];
              if (typeof cell === "string") {
                cell = cell.replace(/\n/g, "\n");
                body[i][j] = { text: cell, margin: [5, 5, 5, 5] };
              }
            }
            
            if (i % 2 === 1) {
              for (var j = 0; j < body[i].length; j++) {
                body[i][j].fillColor = "#f2f2f2";
              }
            }
          }
          doc.content[1].layout = {
            hLineWidth: function (i, node) {
              return 0.5;
            },
            vLineWidth: function (i, node) {
              return 0.5;
            },
            hLineColor: function (i, node) {
              return "#dee2e6";
            },
            vLineColor: function (i, node) {
              return "#dee2e6";
            },
          };
        },
      },
    ],
    initComplete: function () {
      var btns = $(".dt-button");
      btns.removeClass("dt-button");

      var filterBtnHtml = `
        <div class="btn-group ms-2">
          <a type="button" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-3 mt-2" style="min-width: 300px;">
            <div class="mb-2">
              <label for="filter-year">Year</label>
              <select id="filter-year" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-sector">Sector</label>
              <select id="filter-sector" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-category">Category</label>
              <select id="filter-category" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-province">Province</label>
              <select id="filter-province" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-status">Status</label>
              <select id="filter-status" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mt-3">
              <button id="reset-filters" class="btn btn-sm btn-danger w-100">Reset Filters</button>
            </div>
          </ul>
        </div>`;

      $(filterBtnHtml).appendTo(".dt-search");

      $.ajax({
        url: "nohardcode.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
          populateDropdown("#filter-year", data[0]);
          populateDropdown("#filter-sector", data[1]);
          populateDropdown("#filter-category", data[2]);
          populateDropdown("#filter-province", data[3]);
          populateDropdown("#filter-status", data[4]);
        },
      });

      function populateDropdown(selector, options) {
        var dropdown = $(selector);
        options.forEach(function (option) {
          var value = Object.values(option)[0];
          dropdown.append(
            '<option value="' + value + '">' + value + "</option>"
          );
        });
      }

      $(
        "#filter-year, #filter-sector, #filter-category, #filter-province, #filter-status"
      ).on("change", function () {
        applyFilters();
      });

      $("#reset-filters").on("click", function () {
        $(
          "#filter-year, #filter-sector, #filter-category, #filter-province, #filter-status"
        ).val("");

        applyFilters();
      });

      var statusFilter = getUrlParameter("status");
      if (statusFilter) {
        $("#filter-status").val(statusFilter);
        applyFilters();

        updateUrlParameter("status", "");
      }

      function applyFilters() {
        var year = $("#filter-year").val();
        var sector = $("#filter-sector").val();
        var category = $("#filter-category").val();
        var province = $("#filter-province").val();
        var status = $("#filter-status").val();

        table.columns().every(function (index) {
          var column = this;
          var columnTitle = $(column.header()).text().trim().toLowerCase();

          if (columnTitle === "project detail") {
            column.search(province).draw();
          } else {
            switch (columnTitle) {
              case "sector":
                column.search(sector).draw();
                break;
              case "category":
                column.search(category).draw();
                break;
              case "year":
                column.search(year).draw();
                break;
              case "status":
                column.search(status).draw();
                break;
              default:
                column.search("").draw();
                break;
            }
          }
        });
      }
    },
  });
});

$(document).ready(function () {
  var table = $("#beneficiaryDataTable").DataTable({
    responsive: true,
    info: true,
    lengthMenu: [25, 50, 100, 250, 500],
    pageLength: 25,
    dom:
      "<'row pt-2'<'col-sm-12 col-md-4'B><'col-sm-12 col-md-4 d-flex justify-content-center'l><'col-sm-12 col-md-4'f>>" +
      "<'row mt-2'<'col-sm-12'tr>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'i>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'p>>",
    buttons: [
      {
        extend: "print",
        className: "btn btn-info btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          stripHtml: false,
        },
      },
      {
        extend: "excelHtml5",
        className: "btn btn-success btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          format: {
            body: function (data, row, column, node) {
              data = data
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?span>/gi, "")
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?strong>/gi, "")
                .replace(/\n/g, "")
                .split("\n")
                .map((line) => line.trim())
                .join("\n");
              return data;
            },
          },
        },
      },
      {
        extend: "pdfHtml5",
        className: "btn btn-danger btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
        },
        customize: function (doc) {
          var body = doc.content[1].table.body;
          for (var i = 0; i < body.length; i++) {
            for (var j = 0; j < body[i].length; j++) {
              var cell = body[i][j];
              if (typeof cell === "string") {
                cell = cell.replace(/\n/g, "\n");
                body[i][j] = { text: cell, margin: [5, 5, 5, 5] };
              }
            }
            
            if (i % 2 === 1) {
              for (var j = 0; j < body[i].length; j++) {
                body[i][j].fillColor = "#f2f2f2";
              }
            }
          }
          doc.content[1].layout = {
            hLineWidth: function (i, node) {
              return 0.5;
            },
            vLineWidth: function (i, node) {
              return 0.5;
            },
            hLineColor: function (i, node) {
              return "#dee2e6";
            },
            vLineColor: function (i, node) {
              return "#dee2e6";
            },
          };
        },
      },
    ],
    initComplete: function () {
      var btns = $(".dt-button");
      btns.removeClass("dt-button");

      var filterBtnHtml = `
        <div class="btn-group ms-2">
          <a type="button" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-3 mt-2" style="min-width: 300px;">
            <div class="mb-2">
              <label for="filter-sector">Sector</label>
              <select id="filter-sector" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-category">Category</label>
              <select id="filter-category" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="filter-province">Province</label>
              <select id="filter-province" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mt-3">
              <button id="reset-filters" class="btn btn-sm btn-danger w-100">Reset Filters</button>
            </div>
          </ul>
        </div>`;

      $(filterBtnHtml).appendTo(".dt-search");

      $.ajax({
        url: "nohardcode.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
          populateDropdown("#filter-sector", data[1]);
          populateDropdown("#filter-category", data[2]);
          populateDropdown("#filter-province", data[3]);
        },
      });

      function populateDropdown(selector, options) {
        var dropdown = $(selector);
        options.forEach(function (option) {
          var value = Object.values(option)[0];
          dropdown.append(
            '<option value="' + value + '">' + value + "</option>"
          );
        });
      }

      $("#filter-sector, #filter-category, #filter-province").on(
        "change",
        function () {
          applyFilters();
        }
      );

      $("#reset-filters").on("click", function () {
        $("#filter-sector, #filter-category, #filter-province").val("");
        applyFilters();
      });

      function applyFilters() {
        var sector = $("#filter-sector").val();
        var category = $("#filter-category").val();
        var province = $("#filter-province").val();

        table.columns().every(function (index) {
          var column = this;
          var columnTitle = $(column.header()).text().trim().toLowerCase();

          switch (columnTitle) {
            case "sector":
              column.search(sector).draw();
              break;
            case "category":
              column.search(category).draw();
              break;
            case "province":
              column.search(province).draw();
              break;
            default:
              column.search("").draw();
              break;
          }
        });
      }
    },
  });
});

$(document).ready(function () {
  var table = $("#refundDataTable").DataTable({
    responsive: true,
    info: true,
    lengthMenu: [25, 50, 100, 250, 500],
    pageLength: 25,
    dom:
      "<'row pt-2'<'col-sm-12 col-md-4'B><'col-sm-12 col-md-4 d-flex justify-content-center'l><'col-sm-12 col-md-4'f>>" +
      "<'row mt-2'<'col-sm-12'tr>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'i>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'p>>",
    buttons: [
      {
        extend: "print",
        className: "btn btn-info btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          stripHtml: false,
        },
      },
      {
        extend: "excelHtml5",
        className: "btn btn-success btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          format: {
            body: function (data, row, column, node) {
              data = data
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?span>/gi, "")
                .replace(/<[^>]*>/gi, "")
                .replace(/<\/?strong>/gi, "")
                .replace(/\n/g, "")
                .split("\n")
                .map((line) => line.trim())
                .join("\n");
              return data;
            },
          },
        },
      },
      {
        extend: "pdfHtml5",
        className: "btn btn-danger btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
        },
        customize: function (doc) {
          var body = doc.content[1].table.body;
          for (var i = 0; i < body.length; i++) {
            for (var j = 0; j < body[i].length; j++) {
              var cell = body[i][j];
              if (typeof cell === "string") {
                cell = cell.replace(/\n/g, "\n");
                body[i][j] = { text: cell, margin: [5, 5, 5, 5] };
              }
            }
            
            if (i % 2 === 1) {
              for (var j = 0; j < body[i].length; j++) {
                body[i][j].fillColor = "#f2f2f2";
              }
            }
          }
          doc.content[1].layout = {
            hLineWidth: function (i, node) {
              return 0.5;
            },
            vLineWidth: function (i, node) {
              return 0.5;
            },
            hLineColor: function (i, node) {
              return "#dee2e6";
            },
            vLineColor: function (i, node) {
              return "#dee2e6";
            },
          };
        },
      },
    ],
    initComplete: function () {
      var btns = $(".dt-button");
      btns.removeClass("dt-button");

      var filterBtnHtml = `
        <div class="btn-group ms-2">
          <a type="button" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-3 mt-2" style="min-width: 300px;">
            <div class="mb-2">
              <label for="filter-spin-no">Project Spin No.</label>
              <select id="filter-spin-no" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mt-3">
              <button id="reset-filters" class="btn btn-sm btn-danger w-100">Reset Filters</button>
            </div>
          </ul>
        </div>`;

      $(filterBtnHtml).appendTo(".dt-search");

      $.ajax({
        url: "nohardcode.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
          populateDropdown("#filter-spin-no", data[5]);
        },
      });

      function populateDropdown(selector, options) {
        var dropdown = $(selector);
        options.forEach(function (option) {
          var value = Object.values(option)[0];
          dropdown.append(
            '<option value="' + value + '">' + value + "</option>"
          );
        });
      }

      $("#filter-spin-no").on("change", function () {
        applyFilters();
      });

      $("#reset-filters").on("click", function () {
        $("#filter-spin-no").val("");
        applyFilters();
      });

      function applyFilters() {
        var spinNo = $("#filter-spin-no").val();

        table.columns().every(function (index) {
          var column = this;
          var columnTitle = $(column.header()).text().trim().toLowerCase();

          if (columnTitle === "project spin no.") {
            column.search(spinNo).draw();
          } else {
            column.search("").draw();
          }
        });
      }
    },
  });
});

$(document).ready(function () {
  var table = $("#userDataTable").DataTable({
    responsive: true,
    info: true,
    lengthMenu: [25, 50, 100, 250, 500],
    pageLength: 25,
    dom:
      "<'row pt-2'<'col-sm-12 col-md-4'B><'col-sm-12 col-md-4 d-flex justify-content-center'l><'col-sm-12 col-md-4'f>>" +
      "<'row mt-2'<'col-sm-12'tr>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'i>>" +
      "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'p>>",
    buttons: [
      {
        extend: "print",
        className: "btn btn-info btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          stripHtml: false,
        },
      },
      {
        extend: "excelHtml5",
        className: "btn btn-success btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
          format: {
            body: function (data, row, column, node) {
              data = data
                .replace(/<span[^>]*>/gi, "")
                .replace(/<\/span>/gi, "")
                .replace(/<strong>/gi, "")
                .replace(/<\/strong>/gi, "")
                .replace(/<br\s*\/?>/gi, "\n")
                .replace(/\n\s*\n/g, "\n")
                .split("\n")
                .map((line) => line.trim())
                .join("\n");

              return data;
            },
          },
        },
      },
      {
        extend: "pdfHtml5",
        className: "btn btn-danger btn-sm",
        exportOptions: {
          columns: ":not(:last-child)",
        },
        customize: function (doc) {
          var body = doc.content[1].table.body;
          for (var i = 0; i < body.length; i++) {
            for (var j = 0; j < body[i].length; j++) {
              var cell = body[i][j];
              if (typeof cell === "string") {
                cell = cell.replace(/<br\s*\/?>/gi, "\n");
                body[i][j] = { text: cell, margin: [5, 5, 5, 5] };
              }
            }
            
            if (i % 2 === 1) {
              for (var j = 0; j < body[i].length; j++) {
                body[i][j].fillColor = "#f2f2f2";
              }
            }
          }
          doc.content[1].layout = {
            hLineWidth: function (i, node) {
              return 0.5;
            },
            vLineWidth: function (i, node) {
              return 0.5;
            },
            hLineColor: function (i, node) {
              return "#dee2e6";
            },
            vLineColor: function (i, node) {
              return "#dee2e6";
            },
          };
        },
      },
    ],
    initComplete: function () {
      var btns = $(".dt-button");
      btns.removeClass("dt-button");

      var filterBtnHtml = `
        <div class="btn-group ms-2">
          <a type="button" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-3 mt-2" style="min-width: 300px;">
            <div class="mb-2">
              <label for="filter-acc-type">Account Type</label>
              <select id="filter-acc-type" class="form-select form-select-sm mt-1">
                <option value="">-</option>
              </select>
            </div>
            <div class="mt-3">
              <button id="reset-filters" class="btn btn-sm btn-danger w-100">Reset Filters</button>
            </div>
          </ul>
        </div>`;

      $(filterBtnHtml).appendTo(".dt-search");

      $.ajax({
        url: "nohardcode.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
          populateDropdown("#filter-acc-type", data[6]);
        },
      });

      function populateDropdown(selector, options) {
        var dropdown = $(selector);
        options.forEach(function (option) {
          var value = Object.values(option)[0];
          dropdown.append(
            '<option value="' + value + '">' + value + "</option>"
          );
        });
      }

      $("#filter-acc-type").on("change", function () {
        applyFilters();
      });

      $("#reset-filters").on("click", function () {
        $("#filter-acc-type").val("");
        applyFilters();
      });

      function applyFilters() {
        var accType = $("#filter-acc-type").val();

        table.columns().every(function (index) {
          var column = this;
          var columnTitle = $(column.header()).text().trim().toLowerCase();

          if (columnTitle === "account type") {
            column.search(accType).draw();
          } else {
            column.search("").draw();
          }
        });
      }
    },
  });
});

$(document).ready(function () {
  var table = $("#setupTypeDataTable").DataTable();
});

$(document).ready(function () {
  var table = $("#setupPlanDataTable").DataTable();
});

(function ($) {
  "use strict";

  $(document).ready(function () {
    $(window).on("scroll", function () {
      var scrollDistance = $(this).scrollTop();
      if (scrollDistance > 100) {
        $(".scroll-to-top").stop(true, true).fadeIn();
      } else {
        $(".scroll-to-top").stop(true, true).fadeOut();
      }
    });

    $(document).on("click", "a.scroll-to-top", function (e) {
      var $anchor = $(this);
      $("html, body")
        .stop(true, true)
        .animate(
          {
            scrollTop: $($anchor.attr("href")).offset().top,
          },
          1000,
          "easeInOutExpo"
        );
      e.preventDefault();
    });
  });
})(jQuery);
