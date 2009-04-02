$(document).ready(function()
{
  // Load all the behaviours
  task_behaviour();
  search_behaviour();
  tags_behaviour();
  calendar_behaviour();
  note_behaviour();
  form_behaviour();

  // Calendar
  build_calendar();

  // Perfect time
  set_times();
});

//
// parse gmt times into local times
//
function set_times()
{
  $('span.localtime').each(function() {
     var d = $(this).text();
     var date = d.split(' ')[0];
     var time = d.split(' ')[1];

     var yy = parseInt(date.split('-')[0]);
     var mm = parseInt(date.split('-')[1]) - 1;
     var dd = parseInt(date.split('-')[2]);

     var hh = parseInt(time.split(':')[0]);
     var mi = parseInt(time.split(':')[1]);
     var ss = parseInt(time.split(':')[2]);

     var the_date = new Date(yy,mm,dd,hh,mi,ss);

     diff = (new Date()).getTime();
     diff -= the_date.getTime();
     diff = diff / 1000;

     day_diff = Math.floor(diff / 86400);

     var t = day_diff == 0 && (
			diff < 60 && "just now" ||
			diff < 120 && "1 minute ago" ||
			diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
			diff < 7200 && "1 hour ago" ||
			diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
		day_diff == 1 && "Yesterday" ||
		day_diff < 7 && day_diff + " days ago" ||
		day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";

     $(this).text(t);
  });
}

//
// Company/Person form
//
function form_behaviour()
{
  $('div#extra_contact_link a').click(function () {
      $('div#extra_contact').fadeIn();
      $('div#extra_contact_link').fadeOut();

      return false;
  });

  $('#username').focus();

  $('a.delete-field').livequery('click', function () {
      if(this.parentNode.parentNode.getElementsByTagName('div').length > 1)
        this.parentNode.parentNode.removeChild(this.parentNode);
      else
      {
        first_div = this.parentNode.parentNode.getElementsByTagName('div')[0];
        text_field = first_div.getElementsByTagName('input')[0];
        text_field.value = '';
        select_field = first_div.getElementsByTagName('select')[0];
        select_field.options[0].selected = true;
      }
      return false;
  });

  $('a.add-input').livequery('click', function () {
    div = $(this).parent().prev().clone(true);
    $(this).parent().before(div);
    return false;
  });

  $('form.extra-contact').livequery('submit', function () {
    $('form div.phone').each(function(index, e) {
      e.getElementsByTagName('input')[0].name = 'phone' + index;
      e.getElementsByTagName('select')[0].name = 'phone_select' + index;
    });
    $('form div.email').each(function(index, e) {
      e.getElementsByTagName('input')[0].name = 'email' + index;
      e.getElementsByTagName('select')[0].name = 'email_select' + index;
    });
    $('form div.website').each(function(index, e) {
      e.getElementsByTagName('input')[0].name = 'website' + index;
      e.getElementsByTagName('select')[0].name = 'website_select' + index;
    });
    $('form div.address').each(function(index, e) {
      e.getElementsByTagName('textarea')[0].name = 'street' + index;
      e.getElementsByTagName('input')[0].name = 'city' + index;
      e.getElementsByTagName('input')[1].name = 'state' + index;
      e.getElementsByTagName('input')[2].name = 'zip' + index;
      e.getElementsByTagName('select')[0].name = 'address_select' + index;
      e.getElementsByTagName('select')[1].name = 'country' + index;
    });
    if ($('input#company-search').val() == 'Company name')
    {
        $('input#company-search').val('');
    }
    return true;
  });

  $('input#company-search').keyup(function() {
    var val = $(this).attr('value');
    if(val.length > 0)
    {
      $("#spinner").show();

      var post_url = '';
      if($("form#people-add").attr("action") != null)
        post_url = $("form#people-add").attr("action").replace('add', 'companies');
      else
        post_url = $("form#people-edit").attr("action").replace(/edit.*/, 'companies');

      $.post(post_url + '/' + $(this).val(), {},
        function(html){
          $('#company-search-result').html(html);
          $("#spinner").hide();
        }, 'html'
      );
    }
    else
    {
        $('#company-search-result').remove();
    }
  });

  if ($('input#company-search').val() == '')
  {
    $('input#company-search').addClass('empty');
    $('input#company-search').val('Company name');
  };

  $('input#company-search').focus(function() {
    if (this.value == 'Company name') {
            $(this).removeClass('empty');
            $(this).val('');
    }
    else {
        $(this).select();
    }
  });

  $('input#company-search').blur(function() {
      if($(this).val() == '')
      {
        $(this).addClass('empty');
        $(this).val('Company name');
      }
  });

  $('div.autocompleter li').livequery('click', function() {
      $('input#company-search').val($(this).text());
      $('div.autocompleter').html('');
  });
}

//
// Notes
//
function note_behaviour()
{
  $('a#note-options-toggle').livequery('click', function () {
    $('#note-options-toggle').toggle();
    $('#note-options').toggle();
    $('#note-options-close').toggle();
    return false;
  });

  $('a#note-options-close').livequery('click', function () {
    $('#note-options-toggle').toggle();
    $('#note-options').toggle();
    $('#note-options-close').toggle();
    return false;
  });

  $('form#note-form').livequery('submit', function () {
    $('#spinner').show();
    if($('file-attachment').value != '')
      return true;

    $.post("/notes/addnote", $('form#note-form').serialize(),
      function(html){
        $('#notes').html(html);
      }, 'html');
    return false;
  });

  $('a.delete-note').livequery('click', function () {
    return confirm('Are you sure you want to delete this note');
  });
}

//
// calendar
//
function calendar_behaviour()
{
  $('table.calendar td').livequery('click', function() {
    d = new Date();
    var due_date = $($($(this).parents('form')[0]).find('input.due_date')[0]);
    d.setTime(parseInt($(due_date).val()));
    d.setDate(parseInt($(this).text()));
    $(due_date).val(d.getTime());
    build_calendar();
  });

  $('a.date-down').livequery('click', function() {
    d = new Date();
    var due_date = $($($(this).parents('form')[0]).find('input.due_date')[0]);
    d.setTime(parseInt($(due_date).val()));
    if(d.getMonth() == 0)
    {
      d.setMonth(11);
      d.setFullYear(d.getFullYear() - 1);
    }
    else
      d.setMonth(d.getMonth() - 1);
    $(due_date).val(d.getTime());
    build_calendar();
    return false;
  });

  $('a.date-up').livequery('click', function() {
    d = new Date();
    var due_date = $($($(this).parents('form')[0]).find('input.due_date')[0]);
    d.setTime(parseInt($(due_date).val()));
    if(d.getMonth() == 11)
    {
      d.setMonth(0);
      d.setFullYear(d.getFullYear() + 1);
    }
    else
      d.setMonth(d.getMonth() + 1);
    $(due_date).val(d.getTime());
    build_calendar();
    return false;
  });

  $('div.calendar input.time-check').livequery('click', function() {
    var vis = $(this).attr("checked");
    $($(this).next()).attr("disabled", ! vis);
    $($(this).next().next()).attr("disabled", ! vis);
  });
}

//
// Tags
//
function tags_behaviour()
{
  $('#tags-edit-button a.tag-form-url').livequery('click', function() {
      $('#tags-edit-button a').hide();
      $('#tag-edit').show();
      $('#tag-form').show();
      return false;
  });

  $('a#tag-edit-cancel').livequery('click', function() {
      $('#tags-edit-button a').show();
      $('#tag-edit').hide();
      $('#tag-form').hide();
      return false;
  });

  $('form#tag-form').livequery('submit', function() {

      var tag_val = $('input#tag-name').val();
      var assoc_val = $('input#tag-assoc').val();
      var type_val = $('input#tag-type').val();

      $("#tags").load($('#tag-form').attr("action"),
        { tag: tag_val, associated_with_id: assoc_val,
            association_type: type_val },
        function(){
      });
     return false;
  });

  $('a.tag-delete').livequery('click', function() {
    if(confirm('Are you sure you want to delete this tag ?'))
    {
      $("#tags").load($(this).attr('href'),
        {},
        function(){
      });
    }
    return false;
  });
}

//
// People search on dashboard
//
function search_behaviour()
{
    $('form#main-search input').focus();

    $('form#main-search input').keyup(function() {

      var val = $(this).attr('value');
      if(val.length > 0)
      {
        $("#spinner").show();
        $("#results").load($("form#main-search").attr("action") + '/' + val, {},
          function(){
              $("#spinner").hide();
          });
      }
      else $("#results").html('');
    });
}

//
// Tasks
//
function task_behaviour()
{
  $('p#add-task-button').livequery('click', function () {
      $('div#add-tasks').slideDown();
      $('p#add-task-button').fadeOut();
      $('#subject-field').val('');
      return false;
  });

  $('a.task-cancel').livequery('click', function () {
    $('div#add-tasks').slideUp();
    $('p#add-task-button').fadeIn();
    $('li.task-edit').fadeOut();
    return false;
  });

  $('div#tasks li').livequery('mouseover', function() {
    $(this).addClass('hover');
  });

  $('div#tasks li').livequery('mouseout', function() {
    $(this).removeClass('hover');
  });

  $('div#tasks a.delete').livequery('click', function() {
    if(confirm('Are you sure you want to delete this task ?'))
    {
      var li = $(this).parent().parent();
      $(li).addClass('busy');
      $.ajax({
          url: $(this).attr('href'),
          success: function(msg) { $(li).slideUp(); }
      });
    }
    return false;
  });

  $('div#tasks input.complete').livequery('click', function() {
    var li = $(this).parent();
    $(li).addClass('complete');
    $.ajax({
        url: '/tasks/complete/' + $(this).attr('value'),
        success: function(msg) { $(li).slideUp(); }
    });
    return false;
  });

  $('div#tasks a.edit').livequery('click', function() {
    li = $(this).parent().parent();
    $(li).addClass('busy');
    $('.task-edit').remove();
    task_id = 'task_' + $(this).attr('href').substring(
      $(this).attr('href').lastIndexOf('/') + 1,
      $(this).attr('href').length);

    $(li).after('<li class="task-edit" id="'
      + task_id + '" style="display:none"></li>');

    $("#" + task_id).load($(this).attr('href'), {}, function(){
      $(li).removeClass('busy');
      build_calendar();
      $('#' + task_id).slideDown();
    });
    return false;
  });

  $('form#task-form').livequery('submit', function() {
    $.post($(this).attr('action'), $('form#task-form').serialize(),
      function(html){
        $('#tasks').html(html);
      }, 'html');
    return false;
  });

  $('form#task-form-embedded').livequery('submit', function() {
    $.post($(this).attr('action'), $('form#task-form-embedded').serialize(),
      function(html){
        $('#tasks').html(html);
        $('div#add-tasks').slideUp();
        $('p#add-task-button').fadeIn();
        $('li.task-edit').fadeOut();
      }, 'html');
    return false;
  });
}

function build_calendar()
{
    $('input.due_date').each(function(i) {

        if($(this).val() == '')
          $(this).val(new Date().getTime());
        var dd = $(this).next()
        $(dd).val($(this).val());

        var display_date = new Date();
        display_date.setTime(parseInt($(dd).val()));
        var due_date = new Date();
        due_date.setTime(parseInt($(this).val()));
        var date = new Date();
        date.setTime(due_date.getTime());

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May',
                'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                'Nov', 'Dec'];

        $($(this).parents('form').find('span.calendar-title')[0]).text(
          months[date.getMonth()] + ' ' + date.getFullYear());

        var rows = $(this).parents('form').find('table.calendar tr');
        date.setDate(1);
        date.setTime(date.getTime() -
                ((date.getDay() - 1) * 24 * 60 * 60 * 1000));
        for(i = 1; i < rows.length; i++)
        {
            var cells = $(rows[i]).children('td');
            for(x = 0; x < cells.length; x++)
            {
                var cell = $(cells[x]);
                if(display_date.getMonth() == date.getMonth())
                        cell.addClass('this-month');
                else
                        cell.removeClass('this-month');

                if(due_date.getMonth() == date.getMonth() &&
                        due_date.getDate() == date.getDate())
                        cell.addClass('today');
                else
                        cell.removeClass('today');


                cell.text(date.getDate());
                date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
            }
        }
    });
    
}