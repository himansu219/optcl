<table>
    <thead>
    <tr>
        <th colspan="7" style="text-align: center"><strong>Pension Payment Statement Through NEFT/RTGS</strong></th>
    </tr>
    <tr>
        <th><strong>Sl No</strong></th>
        <th><strong>PPO No</strong></th>
        <th><strong>Name of Pensioner</strong></th>
        <th><strong>IFSC Code</strong></th>
        <th><strong>Account No</strong></th>
        <th><strong>Net Pension</strong></th>
        <th><strong>Bank Name</strong></th>
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
            <td>{{ $bill->ifsc_code }}</td>
            <td>{{ $bill->ben_acc_no }}</td>
            <td>{{ $bill->pension_amount }}</td>
            <td>{{ $bill->bank_name }}</td>
        </tr>
    @endforeach
    @if($bills->count()> 0)
        <tr>
            <td colspan="5" style="text-align: right"><strong>Total</strong></td>
            <td>{{ $total_pension_amount }}</td>
            <td></td>
        </tr>
    @endif
    </tbody>
</table>