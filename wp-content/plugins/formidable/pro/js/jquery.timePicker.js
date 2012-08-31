/*
 * A time picker for jQuery
 *
 * Dual licensed under the MIT and GPL licenses.
 * Copyright (c) 2009 Anders Fajerson
 * @name     frmTimePicker
 * @author   Anders Fajerson (http://perifer.se)
 * @example  $("#mytime").frmTimePicker();
 * @example  $("#mytime").frmTimePicker({step:30, startTime:"15:00", endTime:"18:00"});
 *
 * Based on frmTimePicker by Sam Collet (http://www.texotela.co.uk/code/jquery/timepicker/)
 *
 * Options:
 *   step: # of minutes to step the time by
 *   startTime: beginning of the range of acceptable times
 *   endTime: end of the range of acceptable times
 *   separator: separator string to use between hours and minutes (e.g. ':')
 *   show24Hours: use a 24-hour scheme
 */

(function($){
  $.fn.frmTimePicker = function(options) {
    // Build main options before element iteration
    var settings = $.extend({}, $.fn.frmTimePicker.defaults, options);

    return this.each(function() {
      $.frmTimePicker(this, settings);
    });
  };

  $.frmTimePicker = function (elm, settings) {
    var e = $(elm)[0];
    return e.frmTimePicker || (e.frmTimePicker = new jQuery._frmTimePicker(e, settings));
  };

  $.frmTimePicker.version = '0.3';

  $._frmTimePicker = function(elm, settings) {

    var startTime = timeToDate(settings.startTime, settings);
    var endTime = timeToDate(settings.endTime, settings);
	var selected = $(elm).val();

	//remove selected
	if(selected != '')
		$(elm).find('option[value="'+selected+'"]').remove();
	//$('#yourSelect option[value=yourValue]').length > 0;
	

    var times = [];
    var time = new Date(startTime); // Create a new date object.
    while(time <= endTime) {
      times[times.length] = formatTime(time, settings);
      time = new Date(time.setMinutes(time.getMinutes() + settings.step));
    }
	
    // Build the list.
    for(var i = 0; i < times.length; i++) {
      $(elm).append("<option value='"+times[i]+"'>" + times[i] + "</option>");
    }

	$(elm).val(selected);
  }; // End fn;

  // Plugin defaults.
  $.fn.frmTimePicker.defaults = {
    step:30,
    startTime: new Date(0, 0, 0, 0, 0, 0),
    endTime: new Date(0, 0, 0, 23, 30, 0),
    separator: ':',
    show24Hours: true
  };

  // Private functions.

  function formatTime(time, settings) {
    var h = time.getHours();
    var hours = settings.show24Hours ? h : (((h + 11) % 12) + 1);
    var minutes = time.getMinutes();
    return formatNumber(hours) + settings.separator + formatNumber(minutes) + (settings.show24Hours ? '' : ((h < 12) ? ' AM' : ' PM'));
  }

  function formatNumber(value) {
    return (value < 10 ? '0' : '') + value;
  }

  function timeToDate(input, settings) {
    return (typeof input == 'object') ? normaliseTime(input) : timeStringToDate(input, settings);
  }

  function timeStringToDate(input, settings) {
    if (input) {
      var array = input.split(settings.separator);
      var hours = parseFloat(array[0]);
      var minutes = parseFloat(array[1]);

      // Convert AM/PM hour to 24-hour format.
      if (!settings.show24Hours) {
        if (hours === 12 && input.indexOf('AM') !== -1) {
          hours = 0;
        }
        else if (hours !== 12 && input.indexOf('PM') !== -1) {
          hours += 12;
        }
      }
      var time = new Date(0, 0, 0, hours, minutes, 0);
      return normaliseTime(time);
    }
    return null;
  }

  /* Normalise time object to a common date. */
  function normaliseTime(time) {
    time.setFullYear(2001);
    time.setMonth(0);
    time.setDate(0);
    return time;
  }

})(jQuery);
