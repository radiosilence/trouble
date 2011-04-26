
<div id="dialog-kill" title="Register Kill">
    <form>
        <p><label for="pkn">Target PKN</label><br/>
            <input type="text" name="pkn" id="pkn" placeholder="xxxx"/></p>
        <p><label for="weapon">Weapon</label><br/>
        <select name="weapon" class="default" id="weapon">
            <option value="0" class="dropdown_default">Select weapon used...</option>
        </select></p>
        <p><label for="time">Time/Date</label><br/>
        <input type="text" name="when_happened_time" id="when_happened_time" class="timepick" placeholder="Ex. 21:35"/>&nbsp;<input type="text" name="when_happened_date" id="when_happened_date" class="datepick" placeholder="Ex. 2011-03-10"/></p>
        <p><label for="description">Tell the Story...</label><br/>
        <textarea name="description" id="description" placeholder="Ex. I spotted my target and shot her in the face"></textarea></p>
    </form>
</div>