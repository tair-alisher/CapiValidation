using CapiValidation.Data.Configurations;
using CapiValidation.Data.Entities;
using Microsoft.EntityFrameworkCore;

namespace CapiValdation.Data
{
    public class CapiContext : DbContext
    {
        public DbSet<Questionnaire> Questionnaires { get; set; }

        public CapiContext(DbContextOptions<CapiContext> options) : base(options) { }

        protected override void OnModelCreating(ModelBuilder builder)
        {
            builder.ApplyConfiguration(new QuestionnaireConfig());
            base.OnModelCreating(builder);
        }
    }
}