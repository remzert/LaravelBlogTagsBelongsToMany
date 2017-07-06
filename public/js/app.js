$tokenfield = $('#tokenfield');


$tokenfield.tokenfield({
  autocomplete: {
   source: $tokenfield.data('url'),
      minLength: 2,
      delay: 100
  },
  showAutocompleteOnFocus: true
})


    