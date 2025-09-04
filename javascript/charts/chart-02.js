import ApexCharts from 'apexcharts';

// ===== chartTwo
const chart02 = () => {
	const chartEl = document.querySelector('#chartTwo');

	if (!chartEl) return;

	// Get dynamic progress value from PHP (data attribute)
	const progress = parseFloat(chartEl.dataset.progress) || 0;

	const chartTwoOptions = {
		series: [progress],
		colors: ['#465FFF'],
		chart: {
			fontFamily: 'Outfit, sans-serif',
			type: 'radialBar',
			height: 330,
			sparkline: {
				enabled: true,
			},
		},
		plotOptions: {
			radialBar: {
				startAngle: -90,
				endAngle: 90,
				hollow: {
					size: '80%',
				},
				track: {
					background: '#E4E7EC',
					strokeWidth: '100%',
					margin: 5,
				},
				dataLabels: {
					name: {
						show: false,
					},
					value: {
						fontSize: '36px',
						fontWeight: '600',
						offsetY: 60,
						color: '#1D2939',
						formatter: function (val) {
							return val.toFixed(1) + '%';
						},
					},
				},
			},
		},
		fill: {
			type: 'solid',
			colors: ['#465FFF'],
		},
		stroke: {
			lineCap: 'round',
		},
		labels: ['Progress'],
	};

	const chartTwo = new ApexCharts(chartEl, chartTwoOptions);
	chartTwo.render();
};

export default chart02;
