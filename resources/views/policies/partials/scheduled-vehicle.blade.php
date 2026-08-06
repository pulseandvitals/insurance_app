<div class="coc-doc">
    <div class="section-bar">Scheduled Vehicle</div>
    <table class="coc-grid">
        <tr>
            <td><label>Model</label><span>{{ $policy->motorQuote->year_model }}</span></td>
            <td><label>Make</label><span>{{ $policy->motorQuote->brand }}</span></td>
            <td><label>Type of Body</label><span>{{ $policy->motorQuote->vehicle_class }}</span></td>
            <td><label>Color</label><span>{{ $policy->motorQuote->color }}</span></td>
            <td><label>M.V. File No.</label><span>{{ $policy->motorQuote->mv_file_no }}</span></td>
        </tr>
        <tr>
            <td><label>Plate No.</label><span>{{ $policy->motorQuote->plate_no }}</span></td>
            <td><label>Serial /Chassis No.</label><span>{{ $policy->motorQuote->chassis_no }}</span></td>
            <td><label>Motor No.</label><span>{{ $policy->motorQuote->motor_no }}</span></td>
            <td><label>Authorized Capacity</label></td>
            <td><label>Unladen Weight</label><span>Kgs.</span></td>
        </tr>
    </table>
</div>
