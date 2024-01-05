$('#id_category').on('change', () => {
  console.log("filter change");
})


$('#id_sort').on('change', () => {
  console.log("sort change")
})


$('#id_search').on('change', () => {
  console.log("search change")
})


function filter() {
  let form_data = $('#id_filter').serialize();
  console.log(form_data);
  $.ajax({
    headers: {
      "X-CSRFToken": "{{ csrf_token }}"
    },
    type: 'post',
    url: "{% url 'app:obj_like' %}",
    data: form_data,
    success: function (data) {
    }
  });
}
