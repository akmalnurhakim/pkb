<div class="container">
  <h3 class="mb-3 text-white text-center">🕓 Match Schedule</h3>
  <div class="text-center mb-4">
    <!-- Buttons for courts 1–5 -->
    <div class="btn-group" role="group">
      <button class="btn btn-light court-btn" data-court="1">Court 1</button>
      <button class="btn btn-light court-btn" data-court="2">Court 2</button>
      <button class="btn btn-light court-btn" data-court="3">Court 3</button>
      <button class="btn btn-light court-btn" data-court="4">Court 4</button>
      <button class="btn btn-light court-btn" data-court="5">Court 5</button>
    </div>
  </div>

  <!-- Display Area -->
  <div id="matchesContainer" class="row g-3 justify-content-center"></div>
</div>

<script>
$(document).ready(function(){
  $('.court-btn').on('click', function(){
    let court = $(this).data('court');

    // Highlight selected button
    $('.court-btn').removeClass('btn-success').addClass('btn-light');
    $(this).removeClass('btn-light').addClass('btn-success');

    $('#matchesContainer').html('<div class="text-center mt-3">Loading...</div>');

    $.ajax({
      url: 'get_matches.php',
      method: 'GET',
      data: { court: court },
      dataType: 'json',
      success: function(matches){
        $('#matchesContainer').empty();

        if(matches.length === 0) {
          $('#matchesContainer').html('<div class="alert alert-warning text-center">No upcoming matches for Court ' + court + '.</div>');
          return;
        }

        matches.forEach(function(match){
          let card = `
            <div class="col-md-12 col-lg-12">
              <div class="card shadow-sm">
                <div class="card-body">
                  <p class="text-center mb-1">
                    ${new Date(match.match_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                  </p>
                  <h5 class="card-title text-center">${match.team1} vs ${match.team2}</h5>
                </div>
              </div>
            </div>`;
          $('#matchesContainer').append(card);
        });
      },
      error: function(xhr, status, error){
        console.error('AJAX Error:', status, error);
        console.log('Response Text:', xhr.responseText);
        $('#matchesContainer').html('<div class="alert alert-danger text-center">Error loading matches. Check console.</div>');
      }
    });
  });
});
</script>

