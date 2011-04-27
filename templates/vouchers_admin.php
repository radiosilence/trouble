<p><label for="vouchers_type">Voucher Type</label><br />
    <select id="vouchers_type" name="vouchers_type">
    <option value="join">Invitation</option>
    <option value="credit1">1 Credit</option>
    <option value="credit5">5 Credits</option>
    <option value="credit10">10 Credits</option>
    <option value="credit20">20 Credits</option>
    </select></p>
<p><label for="vouchers_number">Number of Vouchers</label><br />
    <input id="vouchers_number" name="vouchers_number" type="number" value="24"></p>
<button id="vouchers_submit" game_id="<?=$game->id?>">Generate Vouchers</button>