<% include BreadCrumb %>

<div id="map" class="map"></div>

<style>
    .map {
        height: 400px;
        width: 100%;
    }
</style>


<script>

    var surveySpecimens = {$Surveys};

    var features = [];
    var pointFeature = new ol.Feature();

    features.push(pointFeature);
    $(document).ready(function () {


        console.log('ready: ');

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


        for (var surveyID in surveySpecimens) {

            var len = surveySpecimens[surveyID].length;

            for (i = 0; i < len; i++) {

                if (surveySpecimens[surveyID][i]) {

                    var survey = surveySpecimens[surveyID][i];
                    var speciesLen = survey.length;
                    for (s = 0; s < speciesLen; s++) {

                        var specimen = survey[s];
                        var lat = +specimen.Latitude;
                        var lon = +specimen.Longitude;
                        var zoom = 13;


                        var point = new ol.Feature();
                        point.setStyle(new ol.style.Style({
                            image: new ol.style.Circle({
                                radius: 4,
                                stroke: new ol.style.Stroke({
                                    color: '#f00',
                                    width: 1
                                })
                            })
                        }));
                        point.setGeometry(new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')));

                        features.push(point);
                    }

                }

            }

        }


        var featureCollection = new ol.Collection(features);

        new ol.layer.Vector({
            map: map,
            source: new ol.source.Vector({
                features: features
            })
        });


    });


</script>
