const ctxTrend = document.getElementById('leave__graph').getContext('2d');

const trendLabels = Object.keys(leaveTrendData);
const trendValues = Object.values(leaveTrendData);

const gradient = ctxTrend.createLinearGradient(0, 0, 0, ctxTrend.canvas.height);
gradient.addColorStop(0, 'rgba(0, 120, 111, 0.8)');
gradient.addColorStop(1, 'rgba(0, 170, 158, 0.2)');

const leaveTrendChart = new Chart(ctxTrend, {
    type: 'bar', // Changed to a bar chart
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Monthly Leave Requests',
            data: trendValues,
            backgroundColor: gradient, // Apply the gradient
            borderColor: '#1b5e20',
            borderWidth: 1,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(0, 170, 158, 0.9)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: false,
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#17524e',
                    font: {
                        weight: 'bold'
                    }
                },
                grid: {
                    display: false, // Cleaned up grid lines
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#17524e',
                    font: {
                        weight: 'bold'
                    },
                    precision: 0, // Ensure whole numbers for counts
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.09)',
                    borderDash: [2, 2], // Subtle grid
                }
            }
        }
    }
});