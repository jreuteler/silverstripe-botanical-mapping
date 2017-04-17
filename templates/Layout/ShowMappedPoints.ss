<% include BreadCrumb %>

<div id="map" class="map">
    <div id="tooltip" class="tooltip"></div>
</div>

<style>
    .map {
        height: 400px;
        width: 100%;
    }
</style>


<script>

    var tooltip = document.getElementById('tooltip');
    var overlay = new ol.Overlay({
        element: tooltip,
        offset: [10, 0],
        positioning: 'bottom-left'
    });
    map.addOverlay(overlay);

    function displayTooltip(evt) {
        var pixel = evt.pixel;
        var feature = map.forEachFeatureAtPixel(pixel, function(feature) {
            return feature;
        });
        tooltip.style.display = feature ? '' : 'none';
        if (feature) {
            overlay.setPosition(evt.coordinate);
            tooltip.innerHTML = feature.get('name');
        }
    };


    var surveySpecimens = {$Surveys};

    var coordinates = [];
    var features = [];
    var pointFeature = new ol.Feature();

    features.push(pointFeature);
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


        for (var surveyID in surveySpecimens) {

            var len = surveySpecimens[surveyID].length;

            if (surveySpecimens[surveyID]) {

                var survey = surveySpecimens[surveyID];
                for (var s in survey) {

                    var specimen = survey[s];
                    var lat = +specimen.Latitude;
                    var lon = +specimen.Longitude;

                    coordinates.push(new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')));
                    var point = new ol.Feature(
                         {name: specimen.Title}
                    );

                    point.setStyle(new ol.style.Style({

                        image: new ol.style.Icon(({
                            anchor: [0.5, 46],
                            anchorXUnits: 'fraction',
                            anchorYUnits: 'pixels',
                            src: 'silverstripe-botanical-mapping/images/tree_icon.png'
                        }))

                    }));

                    point.setGeometry(new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')));
                    features.push(point);
                }

            }

        }

        new ol.layer.Vector({
            map: map,
            source: new ol.source.Vector({
                features: features
            })
        });
        
        map.on('click', displayTooltip);

        var boundingCoordinates = [];
        coordinates.forEach(function (point) {
            boundingCoordinates.push(point.getCoordinates());
        });
        var ext = ol.extent.boundingExtent(boundingCoordinates);
        view.fit(ext, {padding: [250, 250, 30, 150]}, map.getSize());

        map.getView().setZoom(map.getView().getZoom());

    });


</script>
