<div class="space-y-6">
    <h2 class="text-xl font-bold text-white">All Transactions</h2>
    
    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Txn ID</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach($transactions as $row): ?>
                    <tr class="text-sm">
                        <td class="px-6 py-4"><?= $row['username'] ?></td>
                        <td class="px-6 py-4 font-mono"><?= $row['transaction_id'] ?></td>
                        <td class="px-6 py-4 font-bold text-white">$<?= $row['amount'] ?></td>
                        <td class="px-6 py-4 uppercase"><?= $row['payment_method'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[9px] uppercase font-bold 
                                <?= ($row['status'] == 'completed') ? 'bg-green-500/10 text-green-400' : 
                                    (($row['status'] == 'pending') ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400') ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?= date('d M, Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>