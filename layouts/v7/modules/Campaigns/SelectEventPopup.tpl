<div class="modal-header">
    <h4>Select an Event to Send</h4>
</div>

<div class="modal-body">
    <form id="SendEventForm">
        <input type="hidden" name="module" value="Campaigns" />
        <input type="hidden" name="action" value="SendEventToContacts" />
        <input type="hidden" name="record" value="{$CAMPAIGNID}" />

        <label for="eventid">Event:</label>
        <select name="eventid" id="eventid" class="inputElement" required>
            <option value="">-- Select Event --</option>
            {foreach from=$EVENTS item=event}
                <option value="{$event.id}">{$event.label}</option>
            {/foreach}
        </select>
    </form>
</div>

<div class="modal-footer">
    <button class="btn btn-success" onclick="submitEventInvite()">Send Invite</button>
    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
</div>

{literal}
<script type="text/javascript">
function submitEventInvite() {
    var form = jQuery('#SendEventForm');
    var data = form.serialize();

    app.helper.showProgress();

    app.request.post({url: 'index.php', data: data}).then(function(response) {
        app.helper.hideProgress();
        console.log("Response received:", response);

        if (response && response.result) {
            app.helper.showSuccessNotification({message: response.result});
        } else {
            app.helper.showErrorNotification({message: '⚠️ No response returned.'});
        }

        setTimeout(() => app.hideModalWindow(), 1000);
    }).fail(function(error) {
        console.error("Request failed:", error);
        app.helper.hideProgress();
        app.helper.showErrorNotification({message: '❌ Request error occurred.'});
    });
}
</script>
{/literal}



