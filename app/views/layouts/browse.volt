{% if term is defined and term %}
    <div class="content-container">
        <div class="content-header">
            <h4>Displaying results for "{{ term }}"</h4>
        </div>
    </div>
{% endif %}
{{ partial('partial/gallery/layout') }}

{% if term is defined and term %}
    <script type="text/javascript">
        mixpanel.track('{{ _mixpanel_page }} Page', {
            'Search Type': '{{ mixpanelType }}',
            'Search Terms': '{{ term }}',
        });
    </script>
{% endif %}

{% if categoryId is defined and categoryId and categoryName is defined and categoryName %}
    <script type="text/javascript">
        mixpanel.track('Idea Page', {
            'Category Id': '{{ categoryId }}',
            'Category Name': '{{ categoryName }}',
        });
    </script>
{% endif %}