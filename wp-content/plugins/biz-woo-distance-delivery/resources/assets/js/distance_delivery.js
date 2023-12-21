/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 *
function initMap(a, b) {

    // initialize services
    const service = new google.maps.DistanceMatrixService();

    // build request
    const origin = "Phnom Penh";
    const destination = "SaKamchay Mear, Prey Veng";
    const request = {
      origins: [origin],
      destinations: [destination],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false,
    };
  
    // get distance matrix response
    service.getDistanceMatrix(request).then((response) => {
        // put response
        //document.getElementById("response").innerText = JSON.stringify( response, null, 2);
        var distance = response.rows[0].elements[0].distance.text;
        var duration = response.rows[0].elements[0].duration.text;
        var wrap = jQuery('.distance-deliverr-wrap');
            wrap.find('.distance .value').text(distance);
            wrap.find('.time .value').text(duration);
    });
  }
  
  window.initMap = initMap;
  */
  