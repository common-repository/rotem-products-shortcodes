function ajax_get_tables() {

    
}

  tinymce.PluginManager.add('zz_tc_button', function( editor, url ) {
    editor.addButton( 'zz_tc_button', {
      title: "Add shortcode for products",
      image: url + '/Products-shortcode-button.png',
      onclick: function() {

        const rotem = [];
        
        // rotem.push({'type': 'container','name': 'Products', 'html': '<h1 style="text-align:center;">Settings</h1>'});
        // rotem.push({'type': 'textbox','name': 'limit', 'label': 'Limit', 'value': '4'});
        // rotem.push({'type': 'container','name': 'Products', 'html': '<h1 style="text-align:center;">Products</h1>'});

        fetch(ajaxurl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'action=get_ajax_posts'
        })
          .then(function(response) {
            return response.json();
          })
          .then(function(response) {
            var rotem = [];
        
            Object.keys(response).forEach(function(key) {
              var value = response[key];
              rotem.push({'type': 'checkbox', 'name': value.ID, 'label': ' ---- ' + value.post_title, 'value': '' + value.ID + ''});
            });
        
            rotemDialog(editor, rotem);
          })
          .catch(function(error) {
            console.log('Error:', error);
          });
        

        const rotem2 = [{'type': 'checkbox', 'name': 'name', 'label': 'label', 'value': 'value'},{'type': 'checkbox', 'name': 'name', 'label': 'label', 'value': 'value'}];
        console.log(rotem2);
        console.log(rotem);
        

      }
    });
  });

  function rotemDialog(editor,content) {
    editor.windowManager.open( {
        title: 'Choose products to add',
        width: 900,
        height: 300,
        body: content,
        onsubmit: function( e ) {
        
            var ids = '';

            for (var key in e.data) {
              if (e.data[key] === true) {
                ids = ids + key + ',';
              }
            }            

            ids = ids.slice(0, -1);

          let $content = '[products ids="'+ids+'" class="quick-sale" orderby="popularity"]';
          // let $content = '[products ids="'+ids+'" class="quick-sale" limit="'+e.data.limit+'" orderby="popularity"]';
          editor.insertContent( $content );
        }
      });
  }
