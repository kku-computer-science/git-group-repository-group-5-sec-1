namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOfResearchGroup extends Model
{
    use HasFactory;

    protected $table = 'work_of_research_groups';

    protected $fillable = [
        'research_group_id',
        'user_id',
        'role'
    ];

    public function researchGroup()
    {
        return $this->belongsTo(ResearchGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
