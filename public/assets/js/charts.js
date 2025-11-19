
var responseData = JSON.parse(document.currentScript.getAttribute("data-response"));

  if(document.querySelector("#chart-bars")) {
      var ctx = document.getElementById("chart-bars").getContext("2d");
   
      new Chart(ctx, {
          type: "bar",
          data: {
              labels: ["Abr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
              datasets: [
                  {
                      label: "Sales",
                      tension: 0.4,
                      borderWidth: 0,
                      borderRadius: 4,
                      borderSkipped: false,
                      backgroundColor: "#fff",
                      data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                      maxBarThickness: 6,
                  },
              ],
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      display: false,
                  },
              },
              interaction: {
                  intersect: false,
                  mode: "index",
              },
              scales: {
                  y: {
                      grid: {
                          drawBorder: false,
                          display: false,
                          drawOnChartArea: false,
                          drawTicks: false,
                      },
                      ticks: {
                          suggestedMin: 0,
                          suggestedMax: 600,
                          beginAtZero: true,
                          padding: 15,
                          font: {
                              size: 14,
                              family: "Open Sans",
                              style: "normal",
                              lineHeight: 2,
                          },
                          color: "#fff",
                      },
                  },
                  x: {
                      grid: {
                          drawBorder: false,
                          display: false,
                          drawOnChartArea: false,
                          drawTicks: false,
                      },
                      ticks: {
                          display: false,
                      },
                  },
              },
          },
      });
  }

  if (document.querySelector("#chart-line")) {

    var agendamentosPorMes = {};
 
       responseData.forEach(function(agendamento) {
            var data = new Date(agendamento.deleted_at);
            var mes = data.getMonth(); // Mês começa de 0 (janeiro é 0)
            if (!agendamentosPorMes[mes]) {
                agendamentosPorMes[mes] = [];
            }
            agendamentosPorMes[mes].push(agendamento);
        });


    // Array para armazenar os dados dos agendamentos para cada mês
    var dadosAgendamentos = [];

    // Rótulos dos meses
    var meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dec"];

    // Loop de janeiro a dezembro
    for (var mes = 0; mes < 12; mes++) {
        var agendamentosDoMes = agendamentosPorMes[mes] || [];
        var totalAgendamentos = agendamentosDoMes.length;
        dadosAgendamentos.push(totalAgendamentos);
    }

    var ctx1 = document.getElementById("chart-line").getContext("2d");
    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

    new Chart(ctx1, {
        type: "line",
        data: {
            labels: meses, // Usando os rótulos dos meses
            datasets: [{
                label: "Agendamentos",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#5e72e4",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: dadosAgendamentos,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#fbfbfb',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#ccc',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
}

if (document.querySelector("#chart-sales")) {


  

    // Array para armazenar os dados dos agendamentos para cada mês
    var dadosAgendamentos = [];

    // Rótulos dos meses
    var meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dec"];

    // Loop de janeiro a dezembro
     
for (let mes = 0; mes < 12; mes++) {

    let agendamentos = agendamentosPorMes[mes] || [];
    
    let total = agendamentos.reduce((soma, agendamento) => {
        return soma + Number(agendamento.fatura_price || 0);
    }, 0);

   

    dadosAgendamentos.push(total);
}

    var ctx1 = document.getElementById("chart-sales").getContext("2d");
    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
 
    new Chart(ctx1, {
        type: "line",
        data: {
            labels: meses, // Usando os rótulos dos meses
            datasets: [{
                label: "Total do mês",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#5e72e4",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: dadosAgendamentos,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#fbfbfb',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#ccc',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
}

