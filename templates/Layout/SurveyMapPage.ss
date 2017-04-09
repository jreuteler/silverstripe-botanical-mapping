$SurveySearchForm


<label for="track">
    Current position
    <input id="track" type="checkbox"/>
</label>

<div id="map" class="map"></div>

<style>
    .map {
        height: 400px;
        width: 100%;
    }
</style>


<script>
    var surveySpecimens = [];
        <% loop $Surveys %>
        surveySpecimens[$ID] = $SpecimenPositionsJSON;
        <% end_loop %>


    var pointFeature = new ol.Feature();

    $(document).ready(function () {

        pointFeature.setStyle(new ol.style.Style({
            image: new ol.style.Circle({
                radius: 6,
                fill: new ol.style.Fill({
                    color: '#3399CC'
                }),
                stroke: new ol.style.Stroke({
                    color: '#fff',
                    width: 2
                })
            })
        }));


        var len = surveySpecimens.length;
        for (i = 0; i < len; i++) {

            if (surveySpecimens[i]) {

                var survey = surveySpecimens[i];
                var speciesLen = survey.length;
                for (s = 0; s < speciesLen; s++) {

                    var specimen = survey[s];
                    var lat = +specimen.Latitude;
                    var lon = +specimen.Longitude;
                    var zoom = 13;

                    pointFeature.setGeometry(new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')));

                }

            }

        }

        new ol.layer.Vector({
            map: map,
            source: new ol.source.Vector({
                features: [pointFeature]
            })
        });


    });


</script>
