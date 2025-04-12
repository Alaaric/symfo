import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import './chart.js';

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('statsChart');

    if (ctx) {
        // Récupération des données de l'API
        fetch('http://127.0.0.1:8002/images')
            .then(response => response.json())
            .then(data => {
                // Vérificarion des données récupérées dans la console
                console.log(data);

                // Création de deux tableaux : labels et vues
                const labels = [];
                const views = [];

                // Remplissage des tableaux avec les données récupérées
                data.forEach(item => {
                    labels.push(item.name);
                    views.push(item.id);
                });

                // Création du graphique
                new Chart(ctx, {
                    //mettre 'pie' si on souhaite un graphique sous forme de tarte au pomme
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nombre de vues',
                            data: views,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données API :', error);
            });
    }
});


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
