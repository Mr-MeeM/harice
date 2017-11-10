SysteoFileBundle V 0.2

Il s'agit d'un bundle qui permet l'upload de fichier et de les liers aux entités.


1- créer le dossier src/Systeo

2- Copier le dossier FileBundle sous src/Systeo

3- Activer le bundle sous app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Systeo\FileBundle\SysteoFileBundle(),
        // ...
    );
}

4- app/config/routing.yml

systeo_file_file:
    resource: "@SysteoFileBundle/Controller/FileController.php"
    type:     annotation

5- app/config/config.yml

parameters:
    files: '%kernel.root_dir%/../web/files'

6- Générer la table file
   bin/console doctrine:schema:update --force

7- Créer le dossier web/files et lui attribuer les droits en écriture (777)

8- Lier symboliquement les fichier publics du bundle (ks, css et images)
	bin/console asset:install --symlink

9- Ajouter le script suivant dans le vue où vous souhaitez implémenter l'upload de fichier

<script  src="{{ asset('bundles/systeofile/js/main.js') }}" ></script>
<script language="javascript">
var AjaxFileConf = {
        container:'#ajax_file_container',
        entity:'User',
        entity_id:'{{ user.id }}',
        uploadPath:'{{ path('addajaxfile') }}',
        diplayDocs:1,
        displayGallery:1,
        docsGaleryLoadingPath:'{{path('ajaxloadfiles')}}'
    };

load_view_ajax_file();
</script>

10- bin/console cache:clear

ENJOY!
