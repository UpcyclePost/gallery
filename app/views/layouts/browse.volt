{% if term is defined and term %}
    <div class="content-container">
        <div class="content-header">
            <h4>Displaying results for "{{ term }}"</h4>
        </div>
    </div>
{% endif %}
{{ partial('partial/gallery/layout') }}