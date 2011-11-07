function toggleBody(report, number) {
  var linkEl = document.getElementById(report + '_link_' + number + '_1');
  var trElements = document.getElementsByTagName('tr');
  var pattern_head = '^' + report + '_head_' + number + '_';
  var pattern_info = '^' + report + '_info_' + number + '_';
  var pattern_body = '^' + report + '_body_' + number + '_';
  var re_head = new RegExp(pattern_head);
  var re_info = new RegExp(pattern_info);
  var re_body = new RegExp(pattern_body);
  for (var i=0; i < trElements.length; i++) {
    if (re_head.test(trElements[i].id) || re_info.test(trElements[i].id) || re_body.test(trElements[i].id)) {
      var start = report.length + 6;
      if (trElements[i].style.display == 'none') {
        trElements[i].style.display = '';
        document.cookie = report + '_el_' + trElements[i].id.substring(start, start + trElements[i].id.substring(start, trElements[i].id.length).indexOf('_')) + '=show;';
        linkEl.getElementsByTagName('span')[0].innerHTML = '&#8211; ';
      } else {
        trElements[i].style.display = 'none';
        document.cookie = report + '_el_' + trElements[i].id.substring(start, start + trElements[i].id.substring(start, trElements[i].id.length).indexOf('_')) + '=hide;';
        linkEl.getElementsByTagName('span')[0].innerHTML = '+ ';
      }
    }
  }
}

function init(report) {

  var trElements = document.getElementsByTagName('tr');
  var pattern_head = 'tr_head';
  var pattern_info = 'tr_info';
  var pattern_body = 'tr_body';
  var re_head = new RegExp(pattern_head);
  var re_info = new RegExp(pattern_info);
  var re_body = new RegExp(pattern_body);
  for (var i=0; i < trElements.length; i++) {
    if (re_head.test(trElements[i].className) || re_info.test(trElements[i].className) || re_body.test(trElements[i].className)) {
      trElements[i].style.display = 'none';
    }
  }

  if (document.cookie) {
    var start = 0;
    var ende = 0;
    var number = '';
    do {
      start = start + document.cookie.substring(start, document.cookie.length).indexOf(report + '_el_');
      if (start != start - 1) {
        start = start + report.length + 4;
        ende = document.cookie.substring(start, document.cookie.length).indexOf('=');
        number = document.cookie.substring(start, start + ende);
        visibility = document.cookie.substring(start + ende + 1, start + ende + 5);
        if (visibility == 'show') {
          toggleBody(report, number);
        }
      }
    } while (ende != -1);
  }
  
}