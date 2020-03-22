////////////////////////// Ratio Chart //////////////////////////
var rentavi = document.getElementById("ratio-chart").attributes[1].value;
var livrai = document.getElementById("delivered-chart").attributes[1].value;
var rentaviP = 100-rentavi;
var livraiP = 100-livrai;
var ratioFullData = {
    datasets: [{
        data: [rentavi,rentaviP],
        backgroundColor: [
            '#1F9D55',
            '#fff',
        ],
        borderColor: [
            '#ddd',
            '#ddd'
        ]
    }]
};

var ratioChart = new Chart(document.getElementById("ratio-chart").getContext("2d"), {
    type: 'doughnut',
    data: ratioFullData,
    options: {
        tooltips: {
            enabled: false
        }
    }
});

////////////////////////// Delivered Chart //////////////////////////

var deliveredFullData = {
    datasets: [{
        data: [livrai,livraiP],
        backgroundColor: [
            '#1F9D55',
            '#fff',
        ],
        borderColor: [
            '#ddd',
            '#ddd'
        ]
    }]
};

var deliveredChart = new Chart(document.getElementById("delivered-chart").getContext("2d"), {
    type: 'doughnut',
    data: deliveredFullData,
    options: {
        tooltips: {
            enabled: false
        }
    }
});