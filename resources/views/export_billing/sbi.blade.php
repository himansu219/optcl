<table>
    <thead>
    <tr>
        <th colspan="5" style="text-align: center"><strong>Monthly Pension- State Bank Of India</strong></th>
    </tr>
    <tr>
        <th><strong>Sl No</strong></th>
        <th><strong>PPO No</strong></th>
        <th><strong>Name</strong></th>
        <th><strong>A/C No</strong></th>
        <th><strong>Amount</strong></th>
    </tr>
    </thead>
    <tbody>
    @php
        $i = 1;
        $total_pension_amount = 0;
    @endphp
    @foreach($bills as $bill)
        @php
            $total_pension_amount += $bill->pension_amount;
        @endphp
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $bill->ben_ppo_no }}</td>
            <td>{{ $bill->ben_name }}</td>
            <td>{{ $bill->ben_acc_no }}</td>
            <td>{{ $bill->pension_amount }}</td>
        </tr>
    @endforeach
        @if($bills->count()> 0)
        <tr>
            <td colspan="4" style="text-align: right"><strong>Total</strong></td>
            <td>{{ $total_pension_amount }}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3"><strong>FOR GRIDCO PENSION TRUST FUND</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2"><strong>Authorised Signatories</strong></td>
            <td colspan="2"><strong>Authorised Signatories</strong></td>
        </tr>
    </tbody>
</table>