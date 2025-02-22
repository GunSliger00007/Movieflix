
  document.addEventListener('DOMContentLoaded', function() {
    // Select the form and the column where movies will be displayed
    const filterForm = document.getElementById('filterForm');
    const column = document.querySelector('.column');

    // Handle the form submission
    filterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Get selected category and year values
      const category = document.querySelector('select[name="Category"]').value;
      const year = document.querySelector('select[name="year"]').value;

      // Create a query string for the data to be sent
      const queryString = `category=${encodeURIComponent(category)}&year=${encodeURIComponent(year)}`;

      // Create an XMLHttpRequest to fetch the movie list
      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'fetch_movies.php?' + queryString, true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Update the column with the response (movie list)
          column.innerHTML = xhr.responseText;
        }
      };

      // Send the request
      xhr.send();
    });
  });

