<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'étudiant - Plateforme de Stages</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            background-color: #607D8B;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-left: 0.5rem;
        }
        
        .btn-edit {
            background-color: #FF9800;
        }
        
        .btn-delete {
            background-color: #F44336;
        }
        
        .btn-reset {
            background-color: #9C27B0;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .info-section {
            background: #f9f9f9;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-section h3 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.5rem;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        
        .info-value {
            width: 70%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-buttons .btn {
            margin: 0;
            padding: 0.3rem 0.6rem;
            font-size: 0.9rem;
        }
        
        .btn-info {
            background-color: #2196F3;
        }
        
        .status {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 3px;
            font-size: 0.8rem;
            text-align: center;
            width: fit-content;
        }
        
        .status-en-attente {
            background-color: #FFC107;
            color: #212529;
        }
        
        .status-acceptee {
            background-color: #4CAF50;
            color: white;
        }
        
        .status-refusee {
            background-color: #F44336;
            color: white;
        }
        
        .rating-stars {
            display: flex;
            font-size: 1.2rem;
            color: #ddd;
        }
        
        .star {
            margin-right: 2px;
        }
        
        .star.filled {
            color: #FFC107;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-size: 0.8rem;
            background-color: #2196F3;
            color: white;
            margin-left: 0.5rem;
        }
        
        /* Style pour la modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            max-width: 700px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-modal:hover,
        .close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        .modal-header {
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .document-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .document-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Détails de l'étudiant</h2>
            <div>
                <a href="index.php?page=liste_etudiant" class="btn">Retour à la liste</a>
                
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                    <a href="index.php?page=modifier_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-edit">Modifier</a>
                    <a href="index.php?page=supprimer_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">Supprimer</a>
                    <a href="index.php?page=reset_password_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-reset" onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet étudiant ?')">Réinitialiser MDP</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Informations de l'étudiant</h3>
            <div class="info-row">
                <div class="info-label">Email :</div>
                <div class="info-value"><?php echo htmlspecialchars($etudiant['email']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Téléphone :</div>
                <div class="info-value"><?php echo !empty($etudiant['telephone']) ? htmlspecialchars($etudiant['telephone']) : 'Non renseigné'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Promotion :</div>
                <div class="info-value"><?php echo !empty($etudiant['promotion']) ? htmlspecialchars($etudiant['promotion']) : 'Non renseigné'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Spécialité :</div>
                <div class="info-value"><?php echo !empty($etudiant['specialite']) ? htmlspecialchars($etudiant['specialite']) : 'Non renseigné'; ?></div>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Candidatures (<?php echo count($candidatures); ?>)</h3>
            
            <?php if (empty($candidatures)): ?>
                <p>Aucune candidature trouvée pour cet étudiant.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Offre</th>
                            <th>Entreprise</th>
                            <th>CV</th>
                            <th>Lettre de motivation</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidatures as $candidature): ?>
                            <tr>
                                <td>
                                    <a href="index.php?page=voir_offre&id=<?php echo $candidature['offre_stage_id']; ?>">
                                        <?php echo htmlspecialchars($candidature['offre_titre']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($candidature['entreprise_nom']); ?></td>
                                <td>
                                    <?php if (isset($candidature['cv_path']) && !empty($candidature['cv_path'])): ?>
                                        <a href="<?php echo $candidature['cv_path']; ?>" target="_blank">Voir CV</a>
                                    <?php else: ?>
                                        Non disponible
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($candidature['lettre_path']) && !empty($candidature['lettre_path'])): ?>
                                        <a href="<?php echo $candidature['lettre_path']; ?>" target="_blank">Voir lettre</a>
                                    <?php else: ?>
                                        Non disponible
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $candidature['statut']; ?>">
                                        <?php 
                                        switch ($candidature['statut']) {
                                            case 'en_attente':
                                                echo 'En attente';
                                                break;
                                            case 'acceptee':
                                                echo 'Acceptée';
                                                break;
                                            case 'refusee':
                                                echo 'Refusée';
                                                break;
                                            default:
                                                echo $candidature['statut'];
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="#" class="btn btn-info" data-id="<?php echo $candidature['id']; ?>" onclick="showCandidatureDetails(<?php echo $candidature['id']; ?>)">Détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Wishlist -->
        <div class="info-section">
            <h3>Liste de souhaits (<?php echo count($wishlist); ?>)</h3>
            
            <?php if (empty($wishlist)): ?>
                <p>Aucune offre dans la liste de souhaits de cet étudiant.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Offre</th>
                            <th>Entreprise</th>
                            <th>Date d'ajout</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wishlist as $wish): ?>
                            <tr>
                                <td>
                                    <a href="index.php?page=voir_offre&id=<?php echo $wish['offre_stage_id']; ?>">
                                        <?php echo htmlspecialchars($wish['offre_titre']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($wish['entreprise_nom']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($wish['date_ajout'])); ?></td>
                                <td class="action-buttons">
                                    <a href="index.php?page=postuler_offre&id=<?php echo $wish['offre_stage_id']; ?>" class="btn btn-apply">Postuler</a>
                                    <a href="index.php?page=supprimer_wishlist&id=<?php echo $wish['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette offre de la liste de souhaits ?')">Retirer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Notations d'entreprises -->
        <div class="info-section">
            <h3>Évaluations d'entreprises (<?php echo count($notations); ?>)</h3>
            
            <?php if (empty($notations)): ?>
                <p>Cet étudiant n'a pas encore évalué d'entreprises.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notations as $notation): ?>
                            <tr>
                                <td>
                                    <a href="index.php?page=voir_entreprise&id=<?php echo $notation['entreprise_id']; ?>">
                                        <?php echo htmlspecialchars($notation['entreprise_nom']); ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?php echo $i <= $notation['note'] ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($notation['date_notation'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal pour les détails de candidature -->
    <div id="candidatureModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-header">
                <h3>Détails de la candidature</h3>
            </div>
            <div id="candidatureDetails">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
        </div>
    </div>

    <script>
        // Récupérer la modal
        var modal = document.getElementById("candidatureModal");
        var detailsContainer = document.getElementById("candidatureDetails");
        
        // Récupérer le bouton pour fermer la modal
        var closeBtn = document.getElementsByClassName("close-modal")[0];
        
        // Fonction pour afficher les détails de la candidature
        function showCandidatureDetails(candidatureId) {
            // Chercher la candidature correspondante dans les données PHP
            <?php echo 'var candidatures = ' . json_encode($candidatures) . ';'; ?>
            
            var candidature = candidatures.find(function(c) {
                return c.id == candidatureId;
            });
            
            if (candidature) {
                var content = '<div class="info-section">';
                
                // Informations de base
                content += '<div class="info-row"><div class="info-label">Offre :</div><div class="info-value">' + candidature.offre_titre + '</div></div>';
                content += '<div class="info-row"><div class="info-label">Entreprise :</div><div class="info-value">' + candidature.entreprise_nom + '</div></div>';
                
                // Informations du candidat
                if (candidature.nom) {
                    content += '<div class="info-row"><div class="info-label">Nom :</div><div class="info-value">' + candidature.nom + '</div></div>';
                }
                if (candidature.prenom) {
                    content += '<div class="info-row"><div class="info-label">Prénom :</div><div class="info-value">' + candidature.prenom + '</div></div>';
                }
                
                content += '<div class="info-row"><div class="info-label">Statut :</div><div class="info-value"><span class="status status-' + candidature.statut + '">';
                
                // Formatage du statut
                switch (candidature.statut) {
                    case 'en_attente':
                        content += 'En attente';
                        break;
                    case 'acceptee':
                        content += 'Acceptée';
                        break;
                    case 'refusee':
                        content += 'Refusée';
                        break;
                    default:
                        content += candidature.statut;
                }
                
                content += '</span></div></div>';
                
                // Documents
                content += '<div class="info-row"><div class="info-label">Documents :</div><div class="info-value">';
                
                if (candidature.cv_path) {
                    content += '<a href="' + candidature.cv_path + '" class="document-link" target="_blank">Télécharger le CV</a><br>';
                } else {
                    content += 'CV non disponible<br>';
                }
                
                if (candidature.lettre_path) {
                    content += '<a href="' + candidature.lettre_path + '" class="document-link" target="_blank">Télécharger la lettre de motivation</a>';
                } else {
                    content += 'Lettre de motivation non disponible';
                }
                
                content += '</div></div>';
                
                content += '</div>';
                
                detailsContainer.innerHTML = content;
                modal.style.display = "block";
            }
        }
        
        // Fermer la modal quand on clique sur le bouton de fermeture
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
        
        // Fermer la modal si on clique en dehors de la modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html> 