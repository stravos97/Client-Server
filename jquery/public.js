// note: IE8 doesn't support DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {

  var suggestions = document.getElementById("suggestions");
  var form = document.getElementById("search-form");
  var search = document.getElementById("search");



function suggestionsToList(items) {

  var output = '';

  for (i = 0; i < items.length; i++) {
    output += '<li>';
    output += '<a href="search.php?q=' + items[i] + '>';
    output += items[i];
    output += '</a>';
    output += '</li>';
  }

  return output;
}


// Turn JSON  into HTMl
  function showSuggestions(json) {
    var li_list = suggestionsToList(json);
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
