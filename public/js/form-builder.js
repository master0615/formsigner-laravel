$(document).ready(function () {
  var input; // Currently edited item
  var idToUpdate;
  $(".input-drag").draggable({
    containment: $("#form-canva"),
    stop: function (event, element) {
      var fieldID = $(element.helper).data("fieldid");
      updatePosition(element, fieldID);
    }
  });

  $(".input-resize.text").resizable({
    grid: [1, 10000],
    stop: function (event, ui) {
      var fieldID = $(ui.helper).parent().data("fieldid");
      var attributesToUpdate = {};
      attributesToUpdate.id = fieldID;
      attributesToUpdate.width = ui.size.width;
      updateAttribues(attributesToUpdate);
    }
  });


  $(".input-drag").click(function () {
    idToUpdate = null;
    input = $(this).find("input");
    $("#element-params").trigger("reset");
    idToUpdate = $(this).data('fieldid');
    $("#element-id").text($(this).data('fieldid'));

    var type = input.attr('type');
    toggleEditParams(type);

    var length = input.attr('maxlength');
    $("#length").val(length);

    var group = input.attr('name');
    $("#group").val(group);
  });

  function toggleEditParams(type) {
    $("#element-params .form-group").hide();

    $("#element-params ." + type).show();
  }

  $("#element-params").submit(function (e) {
    e.preventDefault();
    var length = $("#length").val();
    var group = $("#group").val();
    input.attr('maxlength', length);
    input.attr('name', group);
    var attributesToUpdate = {};
    attributesToUpdate.id = idToUpdate;
    attributesToUpdate.length = length;
    attributesToUpdate.group = group;

    updateAttribues(attributesToUpdate);
  });


  function updateAttribues(data) {
    $.ajax({
      method: "POST",
      url: apiURL + "/forms/update-element-meta",
      data: data
    })
      .done(function (msg) {
        console.log(msg);
      });


  }


  var apiURL = "http://formsigner.local";

  var elements = [];

  var formData;


  $("#new-text").click(function () {
    $("#form-canva").append("<div class='input-drag'><div class='input-resize'><input type='text' name='field1'></div></div>");
    createNewElement("text");

  });


  $("#new-radio").click(function () {
    $("#form-canva").append("<div class='input-drag'><div class='input-resize'><input type='radio' name='field1'></div></div>");

    createNewElement("radio");
  });

  function createNewElement(type) {
    var element = {
      type: type,
      posX: "100",
      posY: "100",
      group: "default",
      name: "name1"
    };

    elements.push(element);
    $(".input-drag").draggable({
      grid: [20, 20],
      containment: "parent",
      stop: function (event, element) {
        var fieldID = $(element.helper).data("fieldid");
        updatePosition(element, fieldID);
      }
    });

    $.ajax({
      method: "POST",
      url: apiURL + "/forms/save-element",
      data: {
        file: $("#form-canva").data('file-id'),
        elements: elements
      }
    })
      .done(function (msg) {
        console.log(msg);
      });
  }

  function updatePosition(element, id) {
    var formOffset = $("#form-canva").offset();
    element.position.left = element.offset.left - formOffset.left;
    element.position.top = element.offset.top - formOffset.top;
    console.log(element.position);
    $.ajax({
      method: "POST",
      url: apiURL + "/forms/update-element",
      data: {
        id: id,
        position: element.position
      }
    })
      .done(function (msg) {
        console.log(msg);
      });
  }


});