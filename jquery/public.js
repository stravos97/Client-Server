// note: IE8 doesn't support DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {


  var suggestions = document.getElementById("suggestions");
  var form = document.getElementById("search-form");
  var search = document.getElementById("search");






  function suggestionsToList(items) {
    //get the id numbers in a foreach loop print tehm out in the output so the links work


    var output = '';

    for(i=0; i < items.length; i++) {
      for (var b = 0; b < items.length; b++) {
  b++;
      output += '<li>';
      output += '<a href="info_content.php?id=' + getRandomInt(b, items.length) +'&q=q&name=' + items[i] + '">';
      output += items[i];
      output += '</a>';
      output += '</li>';
          }
    }

    return output;
  }

  function getRandomInt(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1)) + min;
  }

// Turn JSON  into HTMl
  function showSuggestions(json) {

    var li_list = suggestionsToList(json);
  //  console.log(li_list);
    suggestions.innerHTML = li_list;
    suggestions.style.display = 'block';
  }

  function getSuggestions() {
    var searchTerm = search.value;


    //no point in search for items if there are less than three charachters
    if (searchTerm.length < 3) {
        suggestions.style.display = 'none';
        return;
    }
/**
*Passes the search term that was typed to autosuggst
 Checks the state of the xhr request, to see whether it is suitible and delivered
 Responds with JSON that is passed and cycled throgh (suggestions to list)
**/
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'autosuggest.php?searchTerm=' + searchTerm, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);
        var json = JSON.parse(result);
        showSuggestions(json);
      }
    };
    xhr.send();
  }

  search.addEventListener("input", getSuggestions);

});
