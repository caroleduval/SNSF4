$(document).ready(function() {


    // Démarrer le carrousel
    $('#myCarousel').find('.item').first().addClass('active');

    // Pagination commentaires
    var $listItems = $('.pagination li');

    $listItems.click(function(){
        $listItems.removeClass('active');
        $(this).addClass('active');

    });

    // Formulaire Trick
    // ______________________________________________________________________________
    // ______________________________________________________________________________
    //
    // Gestion des photos
    // ------------------------------------------------------------------------------
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $containerPhoto = $('div#app_trick_photos');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var indexP = $containerPhoto.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_photo').click(function(e) {
        addPhoto($containerPhoto);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (indexP == 0) {
        addPhoto($containerPhoto);
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $containerPhoto.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire PhotoType
    function addPhoto($containerPhoto) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $containerPhoto.attr('data-prototype')
            .replace(/__name__label__/g, 'Photo n°' + (indexP+1))
            .replace(/__name__/g,        indexP)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $containerPhoto.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        indexP++;
    }

    // La fonction qui ajoute un lien de suppression d'une photo
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<a href="#" class="btn btn-danger decale"><i class="fa fa-trash-o" aria-hidden="true"></a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
    // Gestion des vidéos
    // ------------------------------------------------------------------------------
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $containerVideo = $('div#app_trick_videos');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var indexV = $containerVideo.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_video').click(function(e) {
        addVideo($containerVideo);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (indexV == 0) {
        addVideo($containerVideo);
    } else {
        // S'il existe déjà des videos, on ajoute un lien de suppression pour chacune d'entre elles
        $containerVideo.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire VideoType
    function addVideo($containerVideo) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $containerVideo.attr('data-prototype')
            .replace(/__name__label__/g, 'Video n°' + (indexV+1))
            .replace(/__name__/g,        indexV)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $containerVideo.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        indexV++;
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<a href="#" class="btn btn-danger decale"><i class="fa fa-trash-o" aria-hidden="true"></a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }


    // Pagination commentaire
    // ______________________________________________________________________________
    // ______________________________________________________________________________
    function paginationOnClick() {
        $(".page").each(function(){
            $(this).click(function(e) {
                $('#contenu').load($(this).attr("href"), [], paginationOnClick );
                console.log($(this).attr("href"));
                e.preventDefault();
            })
        });
    }
});
