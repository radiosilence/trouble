<p><label for="vouchers_type">Voucher Type</label><br />
    <select id="vouchers_type" name="vouchers_type">
    <option value="join">Invitation</option>
    <option value="credit1">1 Credit</option>
    <option value="credit5">5 Credits</option>
    <option value="credit10">10 Credits</option>
    <option value="credit20">20 Credits</option>
    </select></p>
<p><label for="vouchers_number">Number of Vouchers</label><br />
    <input id="vouchers_number" name="vouchers_number" type="range" min="14" max="280" step="14" value="14"><sup id="vouchers_numval">14 vouchers / 1 page(s)</sup></p>
<button id="vouchers_submit" game_id="<?=$game->id?>">Generate Vouchers</button>