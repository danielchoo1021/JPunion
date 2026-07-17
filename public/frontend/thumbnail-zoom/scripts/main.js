$('.show-images-thumb').zoomImage();
$('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'})
$('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt')

$('.show-small-img').click(function (e) {
  e.preventDefault();

  var src = $(this).attr('src').split('.');


  if(src[src.length - 1] == 'mp4'){
    $('#show-img').html('<video style="width: 100%;" controls controlsList="nodownload">\
                              <source src="'+$(this).attr('src')+'" type="video/mp4">\
                         </video>');
  }else{
    // $('#show-img').html('<img src="'+$(this).attr('src')+'"  alt="{{ $product->product_name }}" width="100%">');
    $('#show-img a').html('<div class="details-img" style="background-image: url('+$(this).attr('src')+');">\
                         </div>');

  }
  

  $('#show-img').attr('src', $(this).attr('src'))
  $('#big-img').attr('src', $(this).attr('src'))

  $(this).attr('alt', 'now').siblings().removeAttr('alt')
  $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  if ($('#small-img-roll').children().length > 4) {
    if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
    } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
})
// 点击 '>' 下一张
$('#next-img').click(function (){
  var src = $(".show-small-img[alt='now']").next().attr('src').split('.');


  if(src[src.length - 1] == 'mp4'){
    $('#show-img').html('<video style="width: 100%;" controls controlsList="nodownload">\
                              <source src="'+$(".show-small-img[alt='now']").next().attr('src')+'" type="video/mp4">\
                         </video>');
  }else{
    $('#show-img a').html('<div class="details-img" style="background-image: url('+$(".show-small-img[alt='now']").next().attr('src')+');">\
                         </div>');
  }

  $('#show-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $(".show-small-img[alt='now']").next().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
})
// 点击 '<' 上一张
$('#prev-img').click(function (){
  var src = $(".show-small-img[alt='now']").prev().attr('src').split('.');


  if(src[src.length - 1] == 'mp4'){
    $('#show-img').html('<video style="width: 100%;" controls controlsList="nodownload">\
                              <source src="'+$(".show-small-img[alt='now']").prev().attr('src')+'" type="video/mp4">\
                         </video>');
  }else{
    $('#show-img a').html('<div class="details-img" style="background-image: url('+$(".show-small-img[alt='now']").prev().attr('src')+');">\
                         </div>');
  }

  $('#show-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $(".show-small-img[alt='now']").prev().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
})
