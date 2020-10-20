 <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSoaAndSoaNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soa_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('default')->nullable();
            $table->timestamps();
        });

        $data = array(
            array(
                'name' => 'Daily Living Allowance',
                'default' => 1,
            ),
            array(
                'name' => 'Dormitory Fees',
                'default' => 1,
            ),
            array(
                'name' => 'Language Fee',
                'default' => 1,
            ),
            array(
                'name' => 'JFT-basic Refund',
                'default' => 1,
            ),
            array(
                'name' => 'Skills Examination Refund',
                'default' => 1,
            ),
            array(
                'name' => 'Penalty/Damages',
                'default' => 1,
            )
        );

        DB::table('soa_fees')->insert($data);

        Schema::create('soa', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on('students')->onDelete('cascade');
            $table->unsignedInteger('soa_fees_id');
            $table->foreign('soa_fees_id')->references('id')->on('soa_fees')->onDelete('cascade');
            $table->decimal('amount_due', 12, 2)->nullable();
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->unsignedInteger('emp_id')->nullable();
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_soa_and_soa_names');
    }
}
