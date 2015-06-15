{% set last_time = '' %}

{% for event in feed %}
    {% if loop.first %}
        <table style="margin: 0; padding: 2px 5px 5px 2px; line-height: 1.5em;" class="feed-table">
    {% endif %}
    <tr>
    {% set time = event.created|recency %}
    {% set timeline_class = "" %}
    {% if last_time == time %}
        {% set timeline_class = " timeline-hide" %}
    {% endif %}
    <td class="timeline-time{{ timeline_class }}">
        <div>
        {% if last_time != time %}
            {{ time }}
            {% set last_time = time %}
        {% else %}
            &nbsp;
        {% endif %}
        </div>
    </td>

    {% if event.Post %}
    <td style="text-align: center; height: 30px; width: 40px; max-width: 40px; min-width: 40px; vertical-align: middle; padding-right: 10px;" class="feed-thumbnail">
        <img src="{{ event.Post.thumbnail('small') }}" style="margin-bottom: 2px; max-height: 30px; max-width: 30px;">
    </td>
    <td style="font-size: 1.1em;">
    {% else %}
    <td colspan="2" style="height: 30px; font-size: 1.1em;" class="feed-event">
    {% endif %}

    {% if event.event_type is 'post' %}
        {% if event.event_name is 'shared' %}
            {% if event.Sender %}
                <a href="{{ event.Sender.url() }}">{{ event.Sender.user_name }}</a> shared <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
            {% else %}
                Someone shared <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
            {% endif %}
        {% elseif event.event_name is 'liked' %}
            {% if event.Sender %}
                <a href="{{ event.Sender.url() }}">{{ event.Sender.user_name }}</a> liked <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
            {% else %}
                Someone liked <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
            {% endif %}
        {% else %}
            <a href="{{ event.Sender.url() }}">{{ event.Sender.user_name }}</a> posted <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
        {% endif %}
    {% elseif event.event_type is 'listing' %}
        <a href="{{ event.Sender.Shop.url() }}">{{ event.Sender.Shop.name }}</a> posted <a href="{{ event.Post.url() }}">{{ event.Post.title|trim|truncate }}</a>.
    {% elseif event.event_type is 'user' %}
        {% if event.event_name is 'followed' %}
            <a href="{{ event.Sender.url() }}">{{ event.Sender.user_name }}</a> is now following you!
        {% elseif event.event_name is 'messaged' %}
            <a href="{{ event.Sender.url() }}">{{ event.Sender.user_name }}</a> has <a href="{{ url('profile/message/' ~ event.Sender.name ~ '-' ~ event.item_ik) }}">sent you a message</a>.
        {% endif %}
    {% endif %}
    </td>
    </tr>
    {% if loop.last %}
        </table>
    {% endif %}
{% else %}
    {%if viewing == 'following' %}
        <h2>Follow a someone and see their updates here</h2>
        Why not start by
        <a href="{{ url('profile/view/3') }}">following us</a>?
    {% else %}
        <h2>Nothing to see here</h2>
    {% endif %}
{% endfor %}